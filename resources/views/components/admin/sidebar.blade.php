
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

                <x-navigation.item
                    :href="route('admin.dashboard')" active="dashboard" icon="fad text-xs fa-grid-2">
                    @lang('Dashboard')
                </x-navigation.item>

                <x-navigation.item :href="route('admin.announcements.index')" active="announcement" icon="fas text-xs fa-bell">
                    @lang('Announcements')
                </x-navigation.item>

                <x-navigation.group title="Control" icon="fad text-xs fa-shield-alt">
                    <x-navigation.item
                        :href="route('admin.roles.index')" active="roles">
                        @lang('Roles')
                    </x-navigation.item>

                    <x-navigation.item
                        :href="route('admin.modules.list')" active="modules">
                        @lang('Modules')
                    </x-navigation.item>

                    <x-navigation.item
                        :href="route('admin.permissions.index')" active="permissions">
                        @lang('Permissions')
                    </x-navigation.item>
                </x-navigation.group>
            </ul>
        </div>
    </div>
</nav>