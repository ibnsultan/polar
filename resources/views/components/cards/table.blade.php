@props(['class' => null, 'attributes' => []])

<div class="card relative rounded-md {{ $class }}" {{ $attributes }}>
    <div class="flow-root">
        <table class="table table-hover border-t-0">
            <thead class="border-t-0">
                {{ $header }}
            </thead>
            <tbody class="divide-y divide-gray-200 pc-dark:divide-gray-700">
                {{ $body }}
            </tbody>
        </table>
    </div>
</div>