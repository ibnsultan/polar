@props([
    'value' => '0',
    'title' => 'Title',
    'icon' => 'fas fa-circle',
    'class' => null
])

<div 
    class="card rounded-xl p-5 mb-0 {{ $class }} transition-transform duration-300 hover:scale-105 hover:shadow-lg"
    data-target-value="{{ $value }}" >
    <div class="flex items-center space-x-4">
        <div class="bg-[rgb(var(--colors-primary-500)/0.075)] rounded-full p-6 aspect-square w-20 items-center justify-center hidden md:block">
            <i class="{{ $icon }} text-2xl text-[rgb(var(--colors-primary-500))]"></i>
        </div>
        <div class="flex-1 pl-5">
            <span class="value-counter text-4xl font-bold text-gray-900 pc-dark:text-gray-300">0</span>
            <h3 class="text-sm font-semibold text-gray-700 pc-dark:text-gray-400">{{ $title }}</h3>
        </div>
    </div>
</div>