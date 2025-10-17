<x-dialog.modal id="addModuleModal" :title="__('Add New Module')">
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
</x-dialog.modal>