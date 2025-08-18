<x-dialog.modal id="addPermissionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('Add New Permission')</h5>
            <button type="button" class="btn-close" data-pc-modal-dismiss="#addPermissionModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.permissions.store') }}" method="POST" onsubmit="submitForm(event)">
                @csrf
                <div class="mb-3">
                    <label for="add_name" class="form-label">@lang('Permission Name') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="add_name" name="name" required placeholder="@lang('e.g., create users')" onblur="convertToSnakeCase(this)">
                </div>
                <div class="mb-3">
                    <label for="add_module_id" class="form-label">@lang('Module') <span class="text-danger">*</span></label>
                    <select class="form-control" id="add_module_id" name="module_id" required>
                        <option value="" hidden>@lang('Select Module')</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">@lang('Scopes') <span class="text-danger">*</span></label>
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="form-check me-3 inline">
                            <input class="form-check-input form-checkbox" type="checkbox" value="none" id="add_scope_none" name="scopes[]" checked readonly>
                            <label class="form-check-label" for="add_scope_none">@lang('None')</label>
                        </div>
                        <div class="form-check me-3 inline">
                            <input class="form-check-input form-checkbox" type="checkbox" value="owned" id="add_scope_owned" name="scopes[]">
                            <label class="form-check-label" for="add_scope_owned">@lang('Owned')</label>
                        </div>
                        <div class="form-check me-3 inline">
                            <input class="form-check-input form-checkbox" type="checkbox" value="added" id="add_scope_added" name="scopes[]">
                            <label class="form-check-label" for="add_scope_added">@lang('Added')</label>
                        </div>
                        <div class="form-check me-3 inline">
                            <input class="form-check-input form-checkbox" type="checkbox" value="both" id="add_scope_both" name="scopes[]">
                            <label class="form-check-label" for="add_scope_both">@lang('Both')</label>
                        </div>
                        <div class="form-check inline">
                            <input class="form-check-input form-checkbox" type="checkbox" value="all" id="add_scope_all" name="scopes[]" checked readonly>
                            <label class="form-check-label" for="add_scope_all">@lang('All')</label>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary w-full">@lang('Create Permission')</button>
                </div>
            </form>
        </div>
    </div>
</x-dialog.modal>