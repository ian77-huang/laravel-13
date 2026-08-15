<?php

namespace App\Services;

use App\Models\YoutubeChannel;
use App\Support\Cache\CacheHub;
use App\Support\TimeCraft;
use Illuminate\Database\Eloquent\Collection;

class YoutubeService
{
    public function __construct(private readonly CacheHub $cache) {}

    public function getChannels(int $number = 6): Collection
    {
        $seconds = TimeCraft::toMidnightSeconds();
        $cacheKey = constants('Cache_Key_Index_Youtube_Channel_Lists').':'.$number;

        return $this->cache->remember($cacheKey, function () use ($number) {
            $randomIds = YoutubeChannel::query()
                ->where('state', '=', '1')
                ->pluck('id')
                ->shuffle()
                ->take($number);

            return YoutubeChannel::query()
                ->select(['id', 'title', 'branding', 'image'])
                ->whereKey($randomIds)
                ->get();
        }, $seconds);
    }
}
