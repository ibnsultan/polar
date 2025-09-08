
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

                <x-navigation.item href="{{ route('dashboard') }}" icon="fas text-xs fa-grid-2">
                    Dashboard
                </x-navigation-item>

                <li class="pc-item pc-caption">
                    <label>Sample</label>
                    <span class="pc-icon">
                        <i class="fas fa-ellipsis-h text-xs"></i>
                    </span>
                </li>
                
                <x-navigation.group icon="fas fa-filter-list" title="Menu levels">
                    <x-navigation.item>Level 2.1</x-navigation.item>
                    <x-navigation.group title="Level 2.2">
                        <x-navigation.item>Level 3.1</x-navigation.item>
                        <x-navigation.item>Level 3.2</x-navigation.item>
                        <x-navigation.group title="Level 3.3">
                            <x-navigation.item>Level 4.1</x-navigation.item>
                            <x-navigation.item>Level 4.2</x-navigation.item>
                        </x-navigation.group>
                    </x-navigation.group>

                    <x-navigation.group title="Level 2.3">
                        <x-navigation.item>Level 3.1</x-navigation.item>
                        <x-navigation.item>Level 3.2</x-navigation.item>
                        <x-navigation.group title="Level 3.3">
                            <x-navigation.item>Level 4.1</x-navigation.item>
                            <x-navigation.item>Level 4.2</x-navigation.item>
                        </x-navigation.group>
                    </x-navigation.group>
                </x-navigation.group>
                    
            </ul>
        </div>
    </div>
</nav>
