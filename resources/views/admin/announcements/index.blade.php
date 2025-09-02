<x-admin-layout title="Permissions Management">
    <div class="pc-container">
        <div class="pc-content">

            <div class="relative mb-3">
                <h1 class="h3">@lang('Announcements')</h1>
                
                <div class="absolute -top-2 right-0 mr-[0.187rem] md:mr-0">
                    @if(can('create_announcements'))
                        <button class="btn btn-primary" data-pc-animate="fade-in-scale" data-pc-toggle="modal" data-pc-target="#createAnnouncementModal">
                            <i class="fas fa-plus md:hidden"></i>
                            <span class="hidden md:inline">@lang('Create Announcement')</span>
                        </button>
                    @endif
                </div>
            </div>

            @if($announcements->count() > 0)
                <div class="card mt-6">
                    <div class="flow-root">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Created By')</th>
                                    <th>@lang('Target Roles')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Created At')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($announcements as $announcement)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $announcement->title }}</strong>
                                            @if($announcement->image)
                                            <small class="text-muted d-block">
                                                <i class="fas fa-image"></i>
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $announcement->creator->name }}</td>
                                    <td>
                                        @if($announcement->target_roles)
                                            @foreach($announcement->target_roles as $roleId)
                                                @php
                                                    $role = $roles->find($roleId);
                                                @endphp
                                                @if($role)
                                                    <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge bg-info">@lang('All Users')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($announcement->is_active)
                                            <span class="badge bg-success">@lang('Active')</span>
                                        @else
                                            <span class="badge bg-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
                                    <td class="text-end">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="btn btn-sm">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute card w-40 text-start" style="z-index: 1;">
                                                @if(can('update_announcements') && (scope('update_announcements') == 'all' || $announcement->created_by == user()->id))
                                                <a class="dropdown-item" href="#" 
                                                onclick="editAnnouncement({{ $announcement->id }})"
                                                data-pc-toggle="modal" data-pc-target="#editAnnouncementModal">
                                                    @lang('Edit')
                                                </a>
                                                @endif
                                                @if(can('delete_announcements') && (scope('delete_announcements') == 'all' || $announcement->created_by == user()->id))
                                                <a class="dropdown-item !text-red-600" href="#"
                                                onclick="deleteAnnouncement({{ $announcement->id }})">
                                                    @lang('Delete')
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $announcements->links() }}
                    </div>
                </div>
                @else
                    <div class="mt-[10rem]">
                        <x-cards.alert
                            title="No announcements found"
                            message="Create your first announcement to get started"
                        />
                    </div>
                @endif
        </div>
    </div>

    <!-- Create Announcement Modal -->
    @if(can('create_announcements'))
        @include('admin.announcements.partials.add')
    @endif

    <!-- Edit Announcement Modal -->
    @if(can('update_announcements'))
        @include('admin.announcements.partials.edit')
    @endif

    
    <script>
        let announcements = @json($announcements->items());
    </script>
</x-admin-layout>