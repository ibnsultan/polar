<x-dialog.modal id="addRoleModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Add New Role')</h5>
            <button type="button" class="btn-close" data-pc-modal-dismiss="#addRoleModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.roles.store') }}" method="POST" onsubmit="submitForm(event)">
                @csrf
                <div class="mb-3">
                    <label for="add_name" class="form-label">@lang('Role Name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="add_name" name="name" required placeholder="@lang('Enter role name')">
                </div>
                <div class="mb-3">
                    <label for="add_description" class="form-label">@lang('Description')</label>
                    <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="@lang('Enter role description')"></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary w-full">@lang('Create Role')</button>
                </div>
            </form>
        </div>
    </div>
</x-dialog.modal>