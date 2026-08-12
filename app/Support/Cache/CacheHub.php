<?php

namespace App\Support\Cache;

use Closure;
use DateInterval;
use DateTimeInterface;

class CacheHub
{
    private readonly Store $l1;

    private readonly Store $l2;

    /**
     * @param  string  $prefix  快取 key 前綴，避免不同功能互相蓋到
     * @param  string  $l1Store  L1 快取 store 名稱
     * @param  string  $l2Store  L2 快取 store 名稱
     */
    public function __construct(
        string $prefix = '',
        private readonly int|DateInterval|DateTimeInterface|null $ttl = null,
        string $l1Store = 'memcached',
        string $l2Store = 'redis',
    ) {
        $this->l1 = new Store(cache()->store($l1Store), $prefix);
        $this->l2 = new Store(cache()->store($l2Store), $prefix);
    }

    /**
     * Get the value for the given key, resolving via $resolver when both tiers miss.
     *
     * 依序查 L1 → L2（命中回填 L1）→ 兩層都 miss 才執行 $resolver，
     * 結果非 null 時同時寫入 L1 + L2。null 結果不寫入快取。
     *
     * @param  Closure(): mixed  $resolver  兩層快取都 miss 時查 DB 並回傳要快取的值
     */
    public function remember(string $key, Closure $resolver, int|DateInterval|DateTimeInterface|null $ttl = null): mixed
    {
        $ttl ??= $this->ttl;

        $cached = $this->getFromTiers($key, null, $ttl);

        if ($cached !== null) {
            return $cached;
        }

        $value = $resolver();

        if ($value !== null) {
            $this->put($key, $value, $ttl);
        }

        return $value;
    }

    /**
     * Get the value for the given key, falling back to the second tier.
     *
     * L1 命中直接回傳；L1 miss 時查 L2，命中則回填 L1。
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->getFromTiers($key, $default, $this->ttl);
    }

    /**
     * Look up the given key across both tiers, backfilling L1 with $ttl when L2 hits.
     *
     * L1 命中直接回傳；L1 miss 時查 L2，命中則以 $ttl 回填 L1。
     */
    private function getFromTiers(string $key, mixed $default, int|DateInterval|DateTimeInterface|null $ttl): mixed
    {
        $value = $this->l1->get($key);

        if ($value !== null) {
            return $value;
        }

        $value = $this->l2->get($key);

        if ($value !== null) {
            $this->l1->put($key, $value, $ttl);

            return $value;
        }

        return $default;
    }

    /**
     * Store the value in both tiers with an expiration.
     */
    public function put(string $key, mixed $value, int|DateInterval|DateTimeInterface|null $ttl = null): bool
    {
        $ttl ??= $this->ttl;

        return $this->l1->put($key, $value, $ttl) && $this->l2->put($key, $value, $ttl);
    }

    /**
     * Forget the value for the given key in both tiers.
     */
    public function forget(string $key): bool
    {
        return $this->l1->forget($key) && $this->l2->forget($key);
    }
}
