@props([])
<div {{ $attributes->merge(['class' => 'mx-3 mt-2 mb-4.5 shadow-xl']) }}>
    <div
        class="text-foreground h-full w-full rounded-lg border border-gray-200 bg-white p-3 text-center dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        {{ $slot }}
    </div>
</div>
