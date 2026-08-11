@props([
    'classNames' => [
        'legend' => '',
    ],
    'title' => null,
    'icon' => null,
])
@php
    $legendClass = collect([
        'text-foreground float-none m-1 ml-3 w-auto px-3 text-2xl leading-[inherit] dark:text-gray-300',
        $classNames['legend'] ?? '',
    ])
        ->filter()
        ->unique()
        ->implode(' ');
@endphp
<fieldset
    {{ $attributes->merge(['class' => 'm-auto w-full rounded-[0.25rem] border border-gray-200 p-0 dark:border-gray-700']) }}>
    <legend class="{{ $legendClass }}">
        @if (isset($title) && $title->hasActualContent())
            {{ $title }}
        @elseif (isset($icon) && $icon->hasActualContent())
            {{ $icon }}
        @endif
    </legend>
    {{ $slot }}
</fieldset>
