@props([])
<div {{ $attributes->merge(['class' => 'card bg-base-100 text-base card-md mt-5 w-full shadow-sm']) }}>
    <div class="card-body text-base">
        {{ $slot }}
    </div>
</div>
