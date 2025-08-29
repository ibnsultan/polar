@props([
    'href' => 'javascript:void(0)',
    'icon' => null,
    'active' => false,
    'tooltip' => false,
])

@php
    if (is_string($active)) {
        $active = active($active);
    }
@endphp

<li class="{{ $active ? 'pc-item live' : 'pc-item' }}"
    @if($tooltip) data-pc-toggle="tooltip" data-pc-placement="left" data-pc-title="{{ $tooltip }}" @endif>
    <a href="{{ $href }}" class="pc-link">
        @if($icon)
            <span class="pc-micon">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <span class="pc-mtext">{{ $slot }}</span>
    </a>
</li>