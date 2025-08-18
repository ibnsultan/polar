<x-admin-layout title="Modules Management">
    <div class="pc-container">
        <div class="pc-content">

            <div class="relative mb-3">
                <input type="text" class="form-control md:max-w-[400px] py-2.5" placeholder="@lang('Search Modules')" oninput="searchModules(this.value)">

                <div class="absolute top-0 right-0 mt-[0.19rem] mr-[0.187rem] md:mr-0">
                    <button class="btn btn-primary" data-pc-animate="fade-in-scale" data-pc-toggle="modal" data-pc-target="#addModuleModal">
                        <i class="fas fa-plus md:hidden"></i>
                        <span class="hidden md:inline">@lang('Add Module')</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
                @if($modules->count())
                    @foreach($modules as $module)
                        <div class="card h-100 module-card" {{ $module->is_core ? 'disabled' : '' }}>
                            <div class="card-body pb-0">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input form-checkbox" id="module_{{ $module->id }}" {{ $module->is_active ? 'checked' : '' }} onchange="toggleModuleStatus({{ $module->id }})" {{ $module->is_core ? 'disabled' : '' }}>
                                    <label class="form-check-label font-bold ml-3" for="module_{{ $module->id }}">
                                        {{ $module->name }}
                                    </label>
                                </div>

                                <div class="text-muted mt-2">
                                    <p class="mb-1 line-clamp-1">{{ $module->description }}</p>
                                </div>

                                <!-- permissions count -->
                                <div class="mt-2">
                                    <span class="badge bg-gray-300 pc-dark:bg-gray-700">{{ $module->permissions_count }} @lang('permissions')</span>
                                </div>

                                <!-- absolute dropdown actions -->
                                @if(!$module->is_core)
                                    <div class="absolute top-2 right-2">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="btn btn-sm">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute card w-40">
                                                <a class="dropdown-item"
                                                        onclick="editModule({{ $module->id }}, '{{ $module->name }}', '{{ $module->description }}')"
                                                        data-pc-toggle="modal" data-pc-target="#editModuleModal">
                                                    <i class="fas fa-edit mr-2"></i> @lang('Edit')
                                                </a>
                                                <a class="dropdown-item" onclick="deleteModule({{ $module->id }}, '{{ $module->name }}')">
                                                    <i class="fas fa-trash mr-2"></i> @lang('Delete')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <x-cards.alert
                        title="@lang('No Modules Found')"
                        description="@lang('You have not created any modules yet.')"
                    />
                @endif
            </div>
        </div>
    </div>

    @include('admin.modules.partials.add')
    @include('admin.modules.partials.edit')

    <x-script src='admin.modules.scripts.list' type="blade:text/javascript" />
</x-admin-layout>