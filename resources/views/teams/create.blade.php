<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div class="pc-container">
        <div class="pc-content">
            <div class="mx-auto py-10 sm:px-6 lg:px-8">
                @livewire('teams.create-team-form')
            </div>
        </div>
    </div>
</x-app-layout>
