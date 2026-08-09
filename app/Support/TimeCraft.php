<?php

namespace App\Support;

use DateTime;
use DateTimeZone;

class TimeCraft
{
    private DateTime $dateTime;

    private function __construct(?string $time = 'now', ?DateTimeZone $timezone = null)
    {
        $this->dateTime = new DateTime($time ?? 'now', $timezone);
    }

    public static function instance(?string $time = 'now', ?DateTimeZone $timezone = null): static
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new static($time, $timezone);
        } else {
            $instance->dateTime = new DateTime($time ?? 'now', $timezone);
        }

        return $instance;
    }

    public static function toMidnightSeconds(): int
    {
        $now = time();
        $endOfDay = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

        return $endOfDay - $now;
    }
}
