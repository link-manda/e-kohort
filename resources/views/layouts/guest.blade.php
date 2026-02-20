<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SI-PRIMA') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Boxicons CSS -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800">
        <!-- Left Side - Branding Section -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-400 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="SI-PRIMA" class="w-30 h-24 rounded-lg object-contain shadow-lg bg-white p-0.5">
                    <!-- <div>
                        <h1 class="text-2xl font-bold text-white">SI-PRIMA</h1>
                        <p class="text-sm text-blue-200">Klinik Bidan</p>
                    </div> -->
                </div>

                <div class="mt-12">
                    <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                        Sistem Informasi Pelayanan<br>Rekam medik Interaktif & MAndiri
                    </h2>
                    <p class="text-blue-100 text-lg leading-relaxed">
                        Platform digital untuk pencatatan dan pemantauan kesehatan ibu hamil, persalinan,
                        KB, dan imunisasi anak secara terintegrasi.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10">
                <div class="flex items-center space-x-6 text-blue-200 text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <span>Aman & Terpercaya</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z">
                            </path>
                        </svg>
                        <span>Cepat & Efisien</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="SI-PRIMA" class="w-12 h-12 rounded-lg object-contain shadow-lg bg-white p-0.5">
                        <div>
                            <h1 class="text-2xl font-bold text-white">SI-PRIMA</h1>
                            <p class="text-sm text-blue-200">Klinik Bidan</p>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">
                    {{ $slot }}
                </div>

                <!-- Footer Text -->
                <p class="text-center text-blue-100 text-sm mt-6">
                    © {{ date('Y') }} SI-PRIMA. Sistem Informasi Pelayanan Rekam medik Interaktif & MAndiri.
                </p>
            </div>
        </div>
    </div>
    {{-- Auto-refresh CSRF token untuk mencegah 419 Page Expired di halaman login --}}
    <script>
        (function () {
            // Refresh CSRF token setiap 25 menit (session default 120 menit, ini sebagai jaga-jaga)
            const REFRESH_INTERVAL = 25 * 60 * 1000; // 25 menit dalam milliseconds

            function refreshCsrfToken() {
                fetch('/csrf-token/refresh', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.token) {
                        // Update semua hidden input _token di halaman
                        document.querySelectorAll('input[name="_token"]').forEach(el => {
                            el.value = data.token;
                        });
                        // Update meta csrf-token
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) metaTag.setAttribute('content', data.token);
                    }
                })
                .catch(() => {}); // Diam-diam jika gagal (user mungkin offline)
            }

            // Jalankan refresh secara berkala
            setInterval(refreshCsrfToken, REFRESH_INTERVAL);

            // Juga refresh ketika tab kembali aktif setelah lama ditinggal
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) {
                    refreshCsrfToken();
                }
            });
        })();
    </script>
</body>

</html>
