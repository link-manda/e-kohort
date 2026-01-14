@props(['header' => null])

<x-dashboard-layout :header="$header">
    {{ $slot }}
</x-dashboard-layout>
