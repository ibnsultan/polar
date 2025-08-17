@props([
    'src' => null,
    'href' => null,
])

@push('styles')
    @if ($href)
        <link rel="stylesheet" href="{{ $href }}">
    @elseif ($src)
        <style>
            @include($src)
        </style>
    @else
        <style>
            {{ $slot ?? '' }}
        </style>
    @endif
@endpush
