<x-dialog.modal id="editAnnouncementModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Edit Announcement')</h5>
            <button type="button" class="btn-close" data-pc-dismiss="#editAnnouncementModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editAnnouncementForm" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label required">@lang('Title')</label>
                    <input type="text" class="form-control" id="edit_title" name="title" placeholder="@lang('Maintenance Announcement')" required>
                </div>

                <div class="mb-3">
                    <label for="edit_content" class="form-label required">@lang('Content')</label>
                    <textarea class="form-control" id="edit_content" name="content" rows="3" placeholder="@lang('Enter content')" required></textarea>
                </div>

                <div x-data="{ showAdditional: false }" class="my-5">
                    <div @click="showAdditional = !showAdditional" class="flex justify-between cursor-pointer text-muted">
                        <span>@lang('Additional options')</span>
                        <i class="fa" :class="showAdditional ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>

                    <div x-show="showAdditional" x-transition class="mt-3">

                        <div class="mb-3">
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <div id="current_image_display" class="mt-2" style="display: none;">
                                <small class="text-muted">@lang('Current image:')</small>
                                <img id="current_image" src="" alt="Current image" class="img-thumbnail mt-1" style="max-width: 200px;">
                            </div>
                            <small class="form-text text-muted">@lang('Optional. Supported formats: JPG, PNG, GIF. Max size: 2MB')</small>
                        </div>

                        <div class="mb-3">
                            <input type="url" class="form-control" id="edit_action_url" name="action_url" placeholder="https://example.com">
                            <small class="form-text text-muted">@lang('Optional. URL for the announcement action button')</small>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" id="edit_target_roles" name="target_roles[]" data-custom-select data-select-placeholder="Select Roles" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">@lang('Leave empty to show to all users.')</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    @lang('Active')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-full">@lang('Update Announcement')</button>
            </div>
        </form>
    </div>
</x-dialog.modal>

<script>
    function editAnnouncement(id) {
        const announcement = announcements.find(a => a.id === id);
        if (!announcement) return;

        // Update form action
        document.getElementById('editAnnouncementForm').action = `/admin/announcements/update/${id}`;
        
        // Fill form fields
        document.getElementById('edit_title').value = announcement.title;
        document.getElementById('edit_content').value = announcement.content;
        document.getElementById('edit_action_url').value = announcement.action_url || '';
        document.getElementById('edit_is_active').checked = announcement.is_active;

        // Handle target roles
        const targetRolesSelect = document.getElementById('edit_target_roles');
        Array.from(targetRolesSelect.options).forEach(option => {
            option.selected = announcement.target_roles ? announcement.target_roles.includes(parseInt(option.value)) : false;
        });

        // Handle current image
        const currentImageDisplay = document.getElementById('current_image_display');
        const currentImage = document.getElementById('current_image');
        if (announcement.image) {
            currentImage.src = `/storage/${announcement.image}`;
            currentImageDisplay.style.display = 'block';
        } else {
            currentImageDisplay.style.display = 'none';
        }
    }

    async function deleteAnnouncement(id) {
        const result = await Swal.fire({
            title: '@lang("Are you sure?")',
            text: '@lang("Are you sure you want to delete this announcement?")',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '@lang("Yes, delete it!")',
            cancelButtonText: '@lang("Cancel")'
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(`/admin/announcements/destroy/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();
            
            if (result.status) {
                // Show success message (you can customize this based on your notification system)
                toast.success({message: '@lang("Announcement deleted successfully")'});
                window.location.reload();
            } else {
                toast.error({message: result.message || '@lang("Failed to delete announcement")'});
            }
        } catch (error) {
            console.error('Error:', error);
            toast.error({message: '@lang("An error occurred while deleting the announcement")'});
        }
    }
</script>