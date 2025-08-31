<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <div class="relative" x-data="{ showPassword: false }">
                    <x-input id="password" class="block mt-1 w-full pr-10" 
                            x-bind:type="showPassword ? 'text' : 'password'" 
                            name="password" required autocomplete="current-password" autofocus />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-on:click="showPassword = !showPassword">
                        <i class="fas" 
                           x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'" 
                           class="text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="flex mt-4">
                <x-button class="bg-zinc-800 text-white hover:bg-zinc-700 px-4 py-2 rounded w-full">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
