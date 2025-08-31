<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="doe@mail.com" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <div class="relative" x-data="{ showPassword: false }">
                    <x-input id="password" class="block mt-1 w-full pr-10" 
                            x-bind:type="showPassword ? 'text' : 'password'" 
                            placeholder="............" name="password" required autocomplete="current-password" />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            x-on:click="showPassword = !showPassword">
                        <i class="fas" 
                           x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'" 
                           class="text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4"">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Reset password') }}
                    </a>
                @endif
            </div>

            
            <div class="w-full flex my-3">                
                <x-button class="bg-zinc-800 text-white hover:bg-zinc-700 px-4 py-2 rounded w-full">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        @if (JoelButcher\Socialstream\Socialstream::show())
            <x-socialstream />
        @endif

        <!-- register -->
        <div class="mt-5 text-center">
            @if (config('auth.registration.enabled', true))
                {{ __('Don\'t have an account?') }}
                
                <a href="{{ route('register') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:navigate>
                    {{ __('Register') }}
                </a>
            @endif
        </div>
    </x-authentication-card>
</x-guest-layout>
