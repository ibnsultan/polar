
let currentRole = null;

function editRole(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('editRoleForm').action = `{{ route('admin.roles.index') }}/${id}`;
}

function deleteRole(id, name) {
    Swal.fire({
        title: '@lang("Are you sure?")',
        text: `@lang("Delete role"): ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '@lang("Yes, delete it!")',
        cancelButtonText: '@lang("Cancel")'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.roles.index') }}/${id}`, {
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

function managePermissions(roleId) {
    currentRole = roleId;
    
    // Show loading
    document.getElementById('permissionsContent').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> @lang("Loading...")</div>';
    
    fetch(`{{ route('admin.roles.index') }}/${roleId}/permissions`)
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                renderPermissions(data.modules, data.role);
            } else {
                toast.error({ message: data.message });
            }
        })
        .catch(() => {
            toast.error({ message: '@lang("Failed to load permissions")' });
        });
}

function renderPermissions(modules, role) {
    let html = '';
    const scopeOptions = {
        'none': 0,
        'owned': 1,
        'added': 2,
        'both': 3,
        'all': 4
        };

    document.getElementById('permissionsOffcanvasLabel').innerText = `@lang('Permissions for') ${role.name}`;

    modules.forEach(module => {
        if (module.permissions.length > 0) {
            html += `
                <table class="table table-sm table-bordered">
                    <tbody>
            `;
            
            module.permissions.forEach(permission => {
                const currentScope = permission.role_permissions.length > 0 ? permission.role_permissions[0].scope : 0;
                console.log(permission.scopes);
                html += `
                    <tr>
                        <td>${module.name}</td>
                        <td>${permission.name}</td>
                        <td class="text-end">
                            <select class="no-styles-select border w-full" name="permissions[${permission.id}][scope]" onchange="updatePermissionScope('${role.id}', ${permission.id}, this.value)">
                `;
                
                permission.scopes.forEach(scope => {
                    const selected = scopeOptions[scope] === currentScope ? 'selected' : '';
                    html += `<option value="${scopeOptions[scope]}" ${selected}>${scope}</option>`;
                });
                
                html += `
                            </select>
                        </td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
            `;
        }
    });
    
    document.getElementById('permissionsContent').innerHTML = html;
}

// search the table
function searchRoles(query) {
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        const nameCell = row.querySelector('td:first-child');
        if (nameCell) {
            const text = nameCell.textContent.toLowerCase();
            row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
        }
    });
}

function updatePermissionScope(role, permissionId, scope) {
    const updatePermissionRoute = `@route('admin.roles.permissions.update', ':roleId')`
        .replace(':roleId', role);

    // post permission and scope
    fetch(updatePermissionRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            permission_id: permissionId,
            scope: parseInt(scope)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            toast.success({ message: data.message });
        } else {
            toast.error({ message: data.message });
        }
    })
    .catch(() => {
        toast.error({ message: '@lang("Failed to update permission scope")' });
    });
}

function updatePermissions(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const permissions = [];
    
    // Parse form data to get permissions array
    const permissionsData = {};
    for (const [key, value] of formData.entries()) {
        const match = key.match(/permissions\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const permissionId = match[1];
            const field = match[2];
            
            if (!permissionsData[permissionId]) {
                permissionsData[permissionId] = {};
            }
            permissionsData[permissionId][field] = value;
        }
    }
    
    // Convert to array format
    Object.values(permissionsData).forEach(permission => {
        if (permission.scope > 0) { // Only include permissions with scope > 0 (none)
            permissions.push({
                permission_id: parseInt(permission.permission_id),
                scope: parseInt(permission.scope)
            });
        }
    });
    
    fetch(`{{ route('admin.roles.index') }}/${currentRole}/permissions`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ permissions })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            toast.success({ message: data.message });
            // Close offcanvas
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('permissionsOffcanvas'));
            if (offcanvas) {
                offcanvas.hide();
            }
        } else {
            toast.error({ message: data.message });
        }
    })
    .catch(() => {
        toast.error({ message: '@lang("Failed to update permissions")' });
    });
}