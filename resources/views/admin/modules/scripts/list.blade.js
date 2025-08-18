
function editModule(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('editModuleForm').action = `{{ route('admin.modules.list') }}/${id}`;
}

function deleteModule(id, name) {
    Swal.fire({
        title: '@lang("Deleting Module") ' + name,
        text: `@lang("This action is irreversible, do you want to delete this module")`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '@lang("Yes, delete it!")',
        cancelButtonText: '@lang("Cancel")'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.modules.list') }}/${id}`, {
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

function toggleModuleStatus(moduleId) {
    const checkbox = document.getElementById(`module_${moduleId}`);
    const isActive = checkbox.checked;

    const route = `@route('admin.modules.toggle', ':moduleId')`
        .replace(':moduleId', moduleId);

    // simple `get` call
    fetch(route)
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                checkbox.checked = isActive;
                toast.success({message: data.message});
            } else {
                checkbox.checked = !isActive; // revert checkbox state
                toast.error({message: data.message});
            }
        })
        .catch(error => {
            checkbox.checked = !isActive; // revert checkbox state
            toast.error({message: `@lang('An error occurred while toggling module status.')`});
        });
    
}

function searchModules(string) {
    const cards = document.querySelectorAll('.module-card');
    cards.forEach(card => {
        const title = card.querySelector('.form-check-label').innerText.toLowerCase();
        if (title.includes(string.toLowerCase())) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}