<x-admin-layout title="Roles Management">
    <div class="pc-container">
        <div class="pc-content">
            <div class="relative mb-3">
                <input type="text" class="form-control md:max-w-[400px] py-2.5" placeholder="@lang('Search Roles')" oninput="searchRoles(this.value)">

                <div class="absolute top-0 right-0 mt-[0.19rem] mr-[0.187rem] md:mr-0">
                    <button class="btn btn-primary" data-pc-animate="fade-in-scale" data-pc-toggle="modal" data-pc-target="#addRoleModal">
                        <i class="fas fa-plus md:hidden"></i>
                        <span class="hidden md:inline">@lang('Add Role')</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card" style="min-height: calc(100vh - 200px);">
                        <div class="flow-root">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Description')</th>
                                        <th>@lang('Users')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                        </td>
                                        <td>{{ $role->description ?? 'No description' }}</td>
                                        <td>
                                            <span class="badge bg-gray-200 pc-dark:bg-gray-700">{{ $role->users_count }} @lang('users')</span>
                                        </td>
                                        <td class="text-end">
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" class="btn btn-sm">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="absolute card w-40 text-start" style="z-index: 1;">
                                                    <a class="dropdown-item"
                                                        onclick="managePermissions({{ $role->id }})"
                                                        data-pc-toggle="offcanvas" data-pc-target="#permissionsOffcanvas">
                                                        </i> @lang('Permissions')
                                                    </a>
                                                    <a class="dropdown-item"
                                                        onclick="editRole({{ $role->id }}, '{{ $role->name }}', '{{ $role->description }}')"
                                                        data-pc-toggle="modal" data-pc-target="#editRoleModal">
                                                        </i> @lang('Edit Role')
                                                    </a>
                                                    <a class="dropdown-item !text-red-600"
                                                        onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')">
                                                        @lang('Delete Role')
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users-crown fa-2x mb-2 d-block"></i>
                                                @lang('No roles found')
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.roles.partials.add')
    @include('admin.roles.partials.edit')
    @include('admin.roles.partials.permissions')

    <x-script src='admin.roles.scripts.list' type="blade:text/javascript" />
</x-admin-layout>
