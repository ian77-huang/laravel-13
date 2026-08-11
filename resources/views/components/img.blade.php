@props(['src', 'alt' => '', 'class' => '', 'width' => '', 'height' => ''])
<img alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }} x-lazy loading="eager"
    src="{{ $src }}" width="{{ $width }}" height="{{ $height }}" alt="{{ $alt }}">
