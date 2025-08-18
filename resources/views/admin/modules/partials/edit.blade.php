<x-dialog.modal id="editModuleModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Edit Module')</h5>
            <button type="button" class="btn-close" data-pc-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" onsubmit="submitForm(event)" id="editModuleForm">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="edit_name" class="form-label">@lang('Module Name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">@lang('Description') <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-pc-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('Update Module')</button>
                </div>
            </form>
        </div>
    </div>
</x-dialog.modal>