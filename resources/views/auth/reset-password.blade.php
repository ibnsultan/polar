<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <div class="relative" x-data="{ showPassword: false }">
                    <x-input id="password" class="block mt-1 w-full pr-10" 
                            x-bind:type="showPassword ? 'text' : 'password'" 
                            name="password" required autocomplete="new-password" />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-on:click="showPassword = !showPassword">
                        <i class="fas" 
                           x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'" 
                           class="text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <div class="relative" x-data="{ showConfirmPassword: false }">
                    <x-input id="password_confirmation" class="block mt-1 w-full pr-10" 
                            x-bind:type="showConfirmPassword ? 'text' : 'password'" 
                            name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-on:click="showConfirmPassword = !showConfirmPassword">
                        <i class="fas" 
                           x-bind:class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'" 
                           class="text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="flex">
                <x-button class="bg-zinc-800 text-white hover:bg-zinc-700 px-4 py-2 rounded w-full">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
