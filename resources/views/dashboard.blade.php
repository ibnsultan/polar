<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="pc-container">
        <div class="pc-content">
            <x-welcome />
        </div>
    </div>
</x-app-layout>