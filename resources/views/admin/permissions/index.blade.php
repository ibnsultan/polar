<x-admin-layout title="Permissions Management">
    <div class="pc-container">
        <div class="pc-content">

            <div class="relative mb-3">
                <input type="text" class="form-control md:max-w-[400px] py-2.5" placeholder="@lang('Search Permissions')" oninput="searchPermissions(this.value)">

                <div class="absolute top-0 right-0 mt-[0.19rem] mr-[0.187rem] md:mr-0">
                    <button class="btn btn-primary" data-pc-animate="fade-in-scale" data-pc-toggle="modal" data-pc-target="#addPermissionModal">
                        <i class="fas fa-plus md:hidden"></i>
                        <span class="hidden md:inline">@lang('Add Permission')</span>
                    </button>
                </div>
            </div>

            <div>
                @if($permissions->count())
                    <div class="card">
                        <div class="flow-root">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Module')</th>
                                        <th>@lang('Scopes')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <code>{{ $permission->name }}</code>
                                        </td>
                                        <td>{{ $permission->module->name }}</td>
                                        <td>
                                            @foreach($permission->scopes as $scope)
                                                <span class="badge bg-gray-200 pc-dark:bg-gray-700 me-1">{{ ucfirst($scope) }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" 
                                                        onclick="editPermission({{ $permission->id }}, '{{ $permission->name }}', {{ $permission->module_id }}, {{ json_encode($permission->scopes) }})"
                                                        data-pc-toggle="modal" data-pc-target="#editPermissionModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deletePermission({{ $permission->id }}, '{{ $permission->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mt-[10rem]">
                        <x-cards.alert
                            :title="__('No Permissions Found')"
                            :message="__('It looks like you don\'t have any permissions yet.')"
                        />
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.permissions.partials.add')
    @include('admin.permissions.partials.edit')

    <x-script src='admin.permissions.scripts.list' type="blade:text/javascript" />
</x-admin-layout>
