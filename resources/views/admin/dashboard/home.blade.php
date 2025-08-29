<x-admin-layout title="Admin Dashboard">
    <div class="pc-container">
        <div class="pc-content">
            
            <div class="mt-[10rem]">
                <x-cards.alert
                    :title="__('Welcome to the Polar Admin Dashboard')"
                    :message="__('Here you can add what is relevant to your application panel')"
                    />
            </div>
        </div>
    </div>
</x-admin-layout>