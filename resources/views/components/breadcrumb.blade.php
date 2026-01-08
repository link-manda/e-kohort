@props(['items' => []])

<nav class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm text-gray-600 mb-3 md:mb-4">
    <!-- Home Icon -->
    <a href="{{ route('dashboard') }}" class="flex items-center hover:text-blue-600 transition-colors p-1">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
    </a>

    @foreach ($items as $item)
        <!-- Separator -->
        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
        </svg>

        <!-- Breadcrumb Item -->
        @if (isset($item['url']) && !$loop->last)
            <a href="{{ $item['url'] }}" class="hover:text-blue-600 transition-colors">
                {{ $item['label'] }}
            </a>
        @else
            <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
