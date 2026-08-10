<?php

use Illuminate\Support\Arr;

if (! function_exists('constants')) {
    /**
     * Get a value from the centralized constants config.
     *
     * constants('verse_count') => config('constants.verse_count')
     * constants()              => config('constants') (entire array)
     */
    function constants(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return config('constants');
        }

        return Arr::get(config('constants'), $key, $default);
    }
}

if (! function_exists('resizeImageYoutubeChannel')) {
    /**
     * Get the YouTube channel image URL at the given type.
     *
     * resizeImageYoutubeChannel($channel, 800) => https://yt3.googleusercontent.com/...=w800
     * Unsupported types fall back to 88; returns an empty string when the
     * channel data has no valid image format.
     */
    function resizeImageYoutubeChannel(object|array $data, int $type): string
    {
        $format = data_get($data, 'image.format');
        if (! is_string($format)) {
            return '';
        }
        switch ($type) {
            case 88:
            case 800:
            case 240:
                break;
            default:
                $type = 88;
                break;
        }

        return str_replace('${type}', $type, $format);
    }
}
