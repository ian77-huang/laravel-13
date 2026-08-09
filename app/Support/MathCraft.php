<?php

namespace App\Support;

class MathCraft
{
    private function __construct()
    {

    }

    public static function instance(): static
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new static();
        }

        return $instance;
    }
    /**
     * Generate a deterministic pseudo-random integer within the given range
     * based on a seed string. The same seed always yields the same number.
     */
    public static function seededRandomNumber(string $seed, int $min = 1, int $max = 124): int
    {
        return hexdec(substr(hash('sha256', $seed), 0, 8)) % ($max - $min + 1) + $min;
    }
}
