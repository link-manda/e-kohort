<x-guest-layout>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
        <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email"
                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password"
                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer"
                    name="remember">
                <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Hidden reCAPTCHA Token (populated by JS before submit) -->
        <input type="hidden" name="captchaToken" id="captchaToken" />
        <x-input-error :messages="$errors->get('captchaToken')" class="mt-2" />

        <!-- Login Button -->
        <div class="pt-2">
            <button type="submit" id="loginBtn"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                <span>{{ __('Log in') }}</span>
            </button>
        </div>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500">SI-PRIMA — Sistem Informasi Pelayanan Rekam medik Interaktif &amp; MAndiri</span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Akses Terbatas</p>
                    <p class="text-blue-700">Hanya pengguna terdaftar yang dapat mengakses sistem ini. Hubungi administrator jika Anda memerlukan akun.</p>
                </div>
            </div>
        </div>

        <!-- reCAPTCHA Badge Info -->
        <p class="text-xs text-center text-gray-400 mt-2">
            Dilindungi oleh reCAPTCHA —
            <a href="https://policies.google.com/privacy" target="_blank" class="underline hover:text-gray-600">Privasi</a> &amp;
            <a href="https://policies.google.com/terms" target="_blank" class="underline hover:text-gray-600">Syarat</a> berlaku.
        </p>
    </form>

    {{-- reCAPTCHA v3: Load Google script with site key from .env --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form   = document.getElementById('loginForm');
            const btn    = document.getElementById('loginBtn');
            const siteKey = "{{ config('services.recaptcha.site_key') }}";

            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Hold submission until token is ready

                // Disable button to prevent double-submit
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>Memverifikasi...</span>
                `;

                // Execute reCAPTCHA v3 and inject token before submit
                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: 'login' }).then(function (token) {
                        document.getElementById('captchaToken').value = token;
                        form.submit(); // Now submit for real
                    }).catch(function () {
                        // Re-enable button if reCAPTCHA fails to load
                        btn.disabled = false;
                        btn.innerHTML = `
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span>Log in</span>
                        `;
                        alert('Gagal memuat layanan verifikasi keamanan. Periksa koneksi internet Anda.');
                    });
                });
            });
        });
    </script>
</x-guest-layout>
