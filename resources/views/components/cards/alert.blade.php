@props([
    'icon' => 'fa-regular fa-stars',
    'title' => 'Alert Title',
    'message' => 'This is an alert message.'
])

<div class="w-full">
    <div class="p-4 mb-4 flex items-center justify-center">
        <div class="text-center">
            <i class="{{ $icon }} text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-4">{{ $title }}</h3>
            <p class="text-sm max-w-[30rem]">{{ $message }}</p>
        </div>
    </div>
</div>        