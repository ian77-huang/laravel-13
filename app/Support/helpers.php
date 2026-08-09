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
