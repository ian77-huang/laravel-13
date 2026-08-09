---
paths:
  - 'docker-compose*.yml'
  - .env
---

# General

## 開發/正式 Docker 環境完� �分離
不要重製�
�用的 docker-compose.yml 基底。開發用 docker-compose.dev.yml（volume 掛載 + octane --watch 熱更新），正式用 docker-compose.prod.yml（映像 bake 程式碼，非 root octane 使用�
，無 volume 掛載）。正式 nginx 靜�
�資源靠�
�享 volume octane_public：app 啟動時 cp public/. 到該 volume，nginx 唯讀掛載。新增正式環境變數（如 DB_HOST）時�
須同時更新 .env.prod.example 與 docker-compose.prod.yml。

## Redis cache db + octane reload trap
Laravel cache 連 redis 走 config/database.php 的 'cache' connection，預設 db 1（REDIS_CACHE_DB，預設 '1'），不是 db 0。要看到 cache 資料記得用 -n 1。改 .env 後只跑 php artisan octane:reload 不夠：Swoole 的 task worker（Octane::concurrently 用的）不會被 reload，必須 docker restart 整個 app container 才會全部套用。
