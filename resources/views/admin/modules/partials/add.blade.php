<x-dialog.modal id="addModuleModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Add New Module')</h5>
            <button type="button" class="btn-close" data-pc-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.modules.store') }}" method="POST" onsubmit="submitForm(event)">
                @csrf
                <div class="mb-3">
                    <label for="add_name" class="form-label">@lang('Module Name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="add_name" name="name" required placeholder="@lang('Enter module name')">
                </div>
                <div class="mb-3">
                    <label for="add_description" class="form-label">@lang('Description') <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="add_description" name="description" rows="3" required placeholder="@lang('Enter module description')"></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-pc-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('Create Module')</button>
                </div>
            </form>
        </div>
    </div>
</x-dialog.modal>