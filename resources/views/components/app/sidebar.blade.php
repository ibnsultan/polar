
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header flex items-center py-4 px-6 h-header-height">
            <a href="@route('dashboard')" class="b-brand flex items-center gap-3">            
                <img src="/assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo" style="max-width: 120px;" />
            </a>
        </div>
        <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>

                <x-navigation.menu-item href="{{ route('dashboard') }}" icon="fas text-xs fa-grid-2">
                    Dashboard
                </x-menu-item>

                <li class="pc-item pc-caption">
                    <label data-i18n="Widget">Sample</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                    </svg>
                </li>
                
                <x-navigation.group-menu icon="fas fa-filter-list">
                    Menu levels
                    <x-slot name="submenu">
                        <x-navigation.menu-item href="" icon="" >Level 2.1</x-navigation.menu-item>
                        <x-navigation.group-menu icon="fas text-xs">
                            Level 2.2
                            <x-slot name="submenu">
                                <x-navigation.menu-item href="" icon="" >Level 3.1</x-navigation.menu-item>
                                <x-navigation.menu-item href="" icon="" >Level 3.2</x-navigation.menu-item>
                                <x-navigation.group-menu icon="fas text-xs">
                                    Level 3.3
                                    <x-slot name="submenu">
                                        <x-navigation.menu-item href="" icon="" >Level 4.1</x-navigation.menu-item>
                                        <x-navigation.menu-item href="" icon="" >Level 4.2</x-navigation.menu-item>
                                    </x-slot>
                                </x-navigation.group-menu>
                            </x-slot>
                        </x-navigation.group-menu>

                        <x-navigation.group-menu icon="fas text-xs">
                            Level 2.3
                            <x-slot name="submenu">
                                <x-navigation.menu-item href="" icon="" >Level 3.1</x-navigation.menu-item>
                                <x-navigation.menu-item href="" icon="" >Level 3.2</x-navigation.menu-item>
                                <x-navigation.group-menu icon="fas text-xs ">
                                    Level 3.3
                                    <x-slot name="submenu">
                                        <x-navigation.menu-item href="" icon="" >Level 4.1</x-navigation.menu-item>
                                        <x-navigation.menu-item href="" icon="" >Level 4.2</x-navigation.menu-item>
                                    </x-slot>
                                </x-navigation.group-menu>
                            </x-slot>
                        </x-navigation.group-menu>
                    </x-slot>
                </x-navigation.group-menu>
            </ul>
            </ul>
        </div>
    </div>
</nav>