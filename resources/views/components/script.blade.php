@props([
    'type' => 'text/javascript',
    'src' => null,
])

@push('scripts')
    @if (!$src)
        <script type="{{ $type}}">
            {{ $slot ?? '' }}
        </script>
    @else
        @if(strpos($type, 'blade:') === 0)
            <script type="{{ str_replace('blade:', '', $type) }}">
                @include($src)
            </script>
        @else
            <script type="{{ $type}}" src="{{ $src }}"></script>
        @endif
    @endif
@endpush