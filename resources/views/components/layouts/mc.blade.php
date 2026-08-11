@props([])
<div {{ $attributes->merge(['class' => 'm-auto my-5 w-[98%] lg:w-210']) }}>
    <section>
        <div class="min-h-250 flex w-full flex-row gap-2.5">
            <section class="flex-1">
                {{ $slot }}
            </section>
        </div>
    </section>
</div>
