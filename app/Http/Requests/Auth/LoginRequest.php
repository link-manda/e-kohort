<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'        => ['required', 'string', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:6'],
            'captchaToken' => ['required', 'string'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'captchaToken.required' => 'Verifikasi keamanan gagal. Silakan muat ulang halaman.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // --- Step 1: Verify reCAPTCHA v3 token ---
        $this->verifyCaptcha();

        // --- Step 2: Attempt authentication ---
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 300); // 5-minute decay

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // --- Step 3: Check if account is active ---
        if (! Auth::user()->is_active) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey(), 300);

            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator untuk informasi lebih lanjut.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verify the reCAPTCHA v3 token with Google's API.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function verifyCaptcha(): void
    {
        $token     = $this->input('captchaToken');
        $secretKey = config('services.recaptcha.secret_key');
        $minScore  = (float) config('services.recaptcha.min_score', 0.5);

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => $this->ip(),
            ]);

            $result = $response->json();

            // Log for monitoring (without sensitive data)
            Log::channel('single')->info('reCAPTCHA verification', [
                'success'  => $result['success'] ?? false,
                'score'    => $result['score'] ?? 0,
                'action'   => $result['action'] ?? 'unknown',
                'hostname' => $result['hostname'] ?? 'unknown',
                'ip'       => $this->ip(),
            ]);

            if (
                ! ($result['success'] ?? false) ||
                ($result['score'] ?? 0) < $minScore ||
                ($result['action'] ?? '') !== 'login'
            ) {
                RateLimiter::hit($this->throttleKey(), 300);

                throw ValidationException::withMessages([
                    'email' => 'Verifikasi keamanan gagal (terdeteksi sebagai bot). Silakan coba lagi.',
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            // Fail open in development, fail closed in production
            Log::error('reCAPTCHA API error: ' . $e->getMessage());

            if (app()->isProduction()) {
                throw ValidationException::withMessages([
                    'email' => 'Layanan verifikasi keamanan tidak tersedia. Silakan coba beberapa saat lagi.',
                ]);
            }
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     * Uses both email + IP to prevent bypass via IP rotation alone.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
