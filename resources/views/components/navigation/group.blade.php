@props([
    'icon' => null,
    'title' => null,
    'tooltip' => null,
])

<li class="pc-item pc-hasmenu"
    @if($tooltip) data-pc-toggle="tooltip" data-pc-placement="left" data-pc-title="{{ $tooltip }}" @endif>
    <a href="javascript:void(0)" class="pc-link">
    
        @if($icon)
            <span class="pc-micon">
                <i class="{{ $icon }}"></i>
            </span>
        @endif

        <span class="pc-mtext">{{ $title }}</span>
        <span class="pc-arrow">
            <i class="fas mt-1 text-xs fa-chevron-right"></i>
        </span>
    </a>
    <ul class="pc-submenu">
        {{ $slot }}
    </ul>
</li>
