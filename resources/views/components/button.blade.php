<?php !isset($category) ? $category = 'btn-primary' : $category = $category; ?>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'mt-2 btn '. $category]) }}>
    {{ $slot }}
</button>