<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="{{ $icon }}"></i>
        </span>
        <span class="pc-mtext">{{ $slot }}</span>
        <span class="pc-arrow">
            <i class="fas mt-1 text-xs fa-chevron-right"></i>
        </span>
    </a>
    <ul class="pc-submenu">
        {{ $submenu }}
    </ul>
</li>
