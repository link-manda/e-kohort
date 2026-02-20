<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mock a successful reCAPTCHA v3 response from Google's API.
     */
    private function mockCaptchaSuccess(float $score = 0.9): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success'  => true,
                'score'    => $score,
                'action'   => 'login',
                'hostname' => 'localhost',
            ], 200),
        ]);
    }

    /**
     * Mock a failed reCAPTCHA v3 response (bot detected).
     */
    private function mockCaptchaFailure(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success'  => false,
                'score'    => 0.1,
                'action'   => 'login',
                'hostname' => 'localhost',
            ], 200),
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $this->mockCaptchaSuccess();

        $user = User::factory()->create(['is_active' => true]);

        $response = $this->post('/login', [
            'email'        => $user->email,
            'password'     => 'password',
            'captchaToken' => 'valid_test_token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $this->mockCaptchaSuccess();

        $user = User::factory()->create(['is_active' => true]);

        $this->post('/login', [
            'email'        => $user->email,
            'password'     => 'wrong-password',
            'captchaToken' => 'valid_test_token',
        ]);

        $this->assertGuest();
    }

    public function test_captcha_token_is_required_for_login(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
            // No captchaToken
        ]);

        $response->assertSessionHasErrors('captchaToken');
        $this->assertGuest();
    }

    public function test_bot_detected_by_low_captcha_score_cannot_login(): void
    {
        // Score 0.1 is below the 0.5 threshold — bot-like traffic
        $this->mockCaptchaSuccess(0.1);

        $user = User::factory()->create(['is_active' => true]);

        $response = $this->post('/login', [
            'email'        => $user->email,
            'password'     => 'password',
            'captchaToken' => 'low_score_token',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->mockCaptchaSuccess();

        $user = User::factory()->create(['is_active' => false]);

        $response = $this->post('/login', [
            'email'        => $user->email,
            'password'     => 'password',
            'captchaToken' => 'valid_test_token',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_rate_limiting_blocks_after_5_attempts(): void
    {
        $this->mockCaptchaSuccess();

        $user = User::factory()->create(['is_active' => true]);

        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email'        => $user->email,
                'password'     => 'wrong-password',
                'captchaToken' => 'valid_test_token',
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post('/login', [
            'email'        => $user->email,
            'password'     => 'password', // even correct password
            'captchaToken' => 'valid_test_token',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        // Cleanup rate limiter key
        RateLimiter::clear(strtolower($user->email) . '|127.0.0.1');
    }

    public function test_register_route_is_disabled(): void
    {
        $response = $this->get('/register');

        // Should return 404 since route is commented out
        $response->assertStatus(404);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
