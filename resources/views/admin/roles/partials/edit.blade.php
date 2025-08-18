<x-dialog.modal id="editRoleModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Edit Role')</h5>
            <button type="button" class="btn-close" data-pc-modal-dismiss="#editRoleModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" onsubmit="submitForm(event)" id="editRoleForm">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="edit_name" class="form-label">@lang('Role Name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">@lang('Description')</label>
                    <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">@lang('Update Role')</button>
                </div>
            </form>
        </div>
    </div>
</x-dialog.modal>