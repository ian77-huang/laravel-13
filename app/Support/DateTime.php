<?php

namespace App\Support;

class DateTime
{
    /**
     * Get the number of seconds remaining until 23:59:59 today.
     */
    public static function toMidnightSeconds(): int
    {
        $now = time();
        $endOfDay = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

        return $endOfDay - $now;
    }
}
