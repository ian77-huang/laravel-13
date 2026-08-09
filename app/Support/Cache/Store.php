<?php

namespace App\Support\Cache;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class Store
{
    public function __construct(
        private readonly CacheRepository $repository,
        private readonly string $prefix = '',
    ) {}

    /**
     * Get the value for the given key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->get($this->key($key), $default);
    }

    /**
     * Store the value for the given key with an expiration.
     */
    public function put(string $key, mixed $value, int|DateInterval|DateTimeInterface|null $ttl = null): bool
    {
        return $this->repository->put($this->key($key), $value, $ttl);
    }

    /**
     * Get the value for the given key or store the result of the callback.
     */
    public function remember(string $key, int|DateInterval|DateTimeInterface|null $ttl, Closure $callback): mixed
    {
        return $this->repository->remember($this->key($key), $ttl, $callback);
    }

    /**
     * Determine if the given key exists in the cache.
     */
    public function has(string $key): bool
    {
        return $this->repository->has($this->key($key));
    }

    /**
     * Forget the value for the given key.
     */
    public function forget(string $key): bool
    {
        return $this->repository->forget($this->key($key));
    }

    /**
     * Remove all cached values.
     */
    public function flush(): bool
    {
        return $this->repository->clear();
    }

    /**
     * Prefix the given key to avoid collisions across stores.
     */
    private function key(string $key): string
    {
        return $this->prefix === '' ? $key : $this->prefix.':'.$key;
    }
}
