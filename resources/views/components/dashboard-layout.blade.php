@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Kohort Klinik') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            @include('layouts.topbar')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <!-- Page Header -->
                @if ($header)
                    <header class="bg-white border-b border-gray-200">
                        <div class="px-6 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Content -->
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} E-Kohort Klinik. All rights reserved.</p>
                    <p>Sistem Informasi Kesehatan Ibu & Anak</p>
                </div>
            </footer>
        </div>
    </div>

    @livewireScripts
</body>

</html>
