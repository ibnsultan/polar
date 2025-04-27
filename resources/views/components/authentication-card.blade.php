<style>
    [type="password"]::placeholder {
        font-size: 2.5rem;
    }
</style>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100" style="
    background: url(/assets/images/authentication/bg.jpg) no-repeat center center fixed;
    background-repeat: no-repeat;
    background-size: cover;
">
    <div class="w-full sm:max-w-md bg-white shadow-md overflow-hidden sm:rounded-lg"
        style="box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;">
        <div class="flex flex-col sm:justify-center items-center pt-4">
            <img src="/assets/images/logo-dark.svg" alt="Logo" class="w-1/4 h-auto">
        </div>

        <div class="w-full sm:max-w-md mt-4 px-6 py-4">
            {{ $slot }}
        </div>
    </div>
</div>
