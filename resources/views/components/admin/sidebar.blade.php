
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header flex items-center py-4 px-6 h-header-height">
            <a href="@route('dashboard')" class="b-brand flex items-center gap-3">            
                <img src="/assets/images/logo-dark.svg" class="img-fluid logo-lg w-full" alt="logo" style="max-width: 120px;" />
                <img src="/assets/images/logo-sm.png" alt="" class="logo-sm">
            </a>
        </div>
        <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
            <ul class="pc-navbar">

                <x-navigation.menu-item :tooltip="__('Dashboard')"
                    :href="route('admin.dashboard')" active="dashboard" icon="fad text-xs fa-grid-2">
                    @lang('Dashboard')
                </x-navigation.menu-item>

                
                <li class="pc-item pc-caption">
                    <label>Access Control</label>
                </li>

                <x-navigation.menu-item
                    :href="route('admin.roles.index')" active="roles" icon="fad text-xs fa-users-crown">
                    @lang('Roles')
                </x-navigation.menu-item>

                <x-navigation.menu-item
                    :href="route('admin.modules.list')" active="modules" icon="fad text-xs fa-cube">
                    @lang('Modules')
                </x-navigation.menu-item>

                <x-navigation.menu-item
                    :href="route('admin.permissions.index')" active="permissions" icon="fad text-xs fa-key">
                    @lang('Permissions')
                </x-navigation.menu-item>
            </ul>
        </div>
    </div>
</nav>