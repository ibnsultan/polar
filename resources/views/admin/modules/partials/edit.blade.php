<x-dialog.modal id="editModuleModal" :title="__('Edit Module')">
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
</x-dialog.modal>