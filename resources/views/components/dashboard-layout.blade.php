@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Kohort Klinik') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50">
    <a href="#main-content"
        class="fixed top-4 left-4 z-[100] bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg transform -translate-y-[200%] focus:translate-y-0 transition-transform duration-300 ring-2 ring-white">
        Lewati ke konten utama
    </a>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            @include('layouts.topbar')

            <!-- Page Content -->
            <main id="main-content" class="flex-1 overflow-y-auto" tabindex="-1">
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
            <x-footer />
        </div>
    </div>

    @livewireScripts
</body>

</html>
