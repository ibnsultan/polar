<x-dialog.canvas id="permissionsOffcanvas" layout="offcanvas-end" class="!w-full">
    <div class="offcanvas-header !pb-0">
        <h5 class="offcanvas-title font-bold" id="permissionsOffcanvasLabel"></h5>
        <button type="button" class="btn-close" data-pc-dismiss="#permissionsOffcanvas">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="offcanvas-body py-0 mx-auto" style="max-width: 1024px;">
        <form onsubmit="updatePermissions(event)" id="permissionsForm">
            <div id="permissionsContent">
                <!-- Permissions will be loaded here -->
            </div>
        </form>
    </div>
</x-dialog.canvas>