<x-dialog.modal id="createAnnouncementModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Create Announcement')</h5>
            <button type="button" class="btn-close" data-pc-dismiss="#createAnnouncementModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)" data-postRequest="handleAnnouncementResponse">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label required">@lang('Title')</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="@lang('Maintenance Announcement')" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label required">@lang('Content')</label>
                    <textarea class="form-control" id="content" name="content" rows="3" placeholder="@lang('Enter content')" required></textarea>
                </div>

                <div x-data="{ showAdditional: false }" class="my-5">
                    <div @click="showAdditional = !showAdditional" class="flex justify-between cursor-pointer text-muted">
                        <span>@lang('Additional options')</span>
                        <i class="fa" :class="showAdditional ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>

                    <div x-show="showAdditional" x-transition class="mt-3">

                        <div class="mb-3">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">@lang('Optional. Supported formats: JPG, PNG, GIF. Max size: 2MB')</small>
                        </div>

                        <div class="mb-3">
                            <input type="url" class="form-control" id="action_url" name="action_url" placeholder="https://example.com">
                            <small class="form-text text-muted">@lang('Optional. URL for the announcement action button')</small>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="target_roles" name="target_roles[]" data-custom-select data-select-placeholder="Select Roles" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">@lang('Leave empty to show to all users.')</small>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-full">@lang('Create Announcement')</button>
            </div>
        </form>
    </div>
</x-dialog.modal>
