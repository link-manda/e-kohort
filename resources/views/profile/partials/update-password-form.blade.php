<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Kata Sandi Saat Ini
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="md:col-span-1">
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Kata Sandi Baru
            </label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="md:col-span-1">
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                Konfirmasi Kata Sandi
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-blue-900">Tips Keamanan Kata Sandi</h4>
                <ul class="text-sm text-blue-800 mt-1 space-y-1">
                    <li>• Minimal 8 karakter</li>
                    <li>• Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                    <li>• Jangan gunakan kata sandi yang sama untuk akun lain</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <div class="flex items-center space-x-2">
            @if (session('status') === 'password-updated')
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-green-800">Kata sandi berhasil diperbarui!</p>
            @endif
        </div>

        <button type="submit"
            class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                </path>
            </svg>
            Ubah Kata Sandi
        </button>
    </div>
</form>
