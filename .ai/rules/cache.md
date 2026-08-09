---
paths:
  - 'app/Support/Cache/**'
---

# Cache

## L1+L2 快取統一用 CacheHub
兩層快取統一用 App\Support\Cache\CacheHub。建構子收 prefix、預設 TTL 與 store 名稱（l1Store/l2Store 可覆寫成 array 供測試），內部自動建 L1=memcached + L2=redis 兩個 Store。DB 查詢 callback 由 remember($key, $resolver, $ttl) 每次呼叫時傳入，不再放建構子。流程：L1 命中即回 → miss 查 L2（命中回填 L1）→ 兩層 miss 跑 resolver 並同時寫入 L1+L2。null 結果不寫入。方法：remember/get/put/forget。
