---
paths:
  - config/cache.php
---

# Config

## serializable_classes must be true (not false) for object caches
In Laravel 12, cache.serializable_classes => false means ALLOW NO classes. RedisStore then does unserialize($value, ['allowed_classes' => false]), so every cached object (including stdClass) reads back as __PHP_Incomplete_Class. This is hidden while L1 memcached hits, but surfaces as a 500 on the first request after memcached restarts (L1 empty -> L2 redis read). Use true (allow all) since CacheHub caches stdClass in the redis tier.
