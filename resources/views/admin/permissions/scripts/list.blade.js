function convertToSnakeCase(input) {
    input.value = input.value.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '');
}

function editPermission(id, name, moduleId, scopes) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_module_id').value = moduleId;
    
    // Clear all checkboxes first
    document.querySelectorAll('#editPermissionModal input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    
    // Check the appropriate scope checkboxes
    scopes.forEach(scope => {
        const checkbox = document.getElementById(`edit_scope_${scope}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    
    document.getElementById('editPermissionForm').action = `{{ route('admin.permissions.index') }}/${id}`;
}

function deletePermission(id, name) {
    Swal.fire({
        title: '@lang("Are you sure?")',
        text: `@lang("Delete permission"): ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '@lang("Yes, delete it!")',
        cancelButtonText: '@lang("Cancel")'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.permissions.index') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    toast.success({ message: data.message });
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toast.error({ message: data.message });
                }
            })
            .catch(() => {
                toast.error({ message: '@lang("Unknown Error Occurred")' });
            });
        }
    });
}
