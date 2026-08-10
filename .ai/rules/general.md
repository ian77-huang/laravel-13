---
paths:
  - 'docker-compose*.yml'
  - .env
  - docker-compose.dev.yml
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
Laravel cache 連 redis 走 config/database.php 的 'cache' connection，預設 db 1（REDIS_CACHE_DB，預設 '1'），不是 db 0。要看到 cache 資料記得用 -n 1。改 .env 後只跑 php artisan octane:reload 不夠：Swoole 的 task worker（Octane::concurrently 用的）不會被 reload，�
須 docker restart 整個 app container 才會�
�部套用。

## Dev 用 vite service 跑 Vite dev server，正式用 Dockerfile � �建 npm run build
Dev 環境的 Vite dev server 由 docker-compose.dev.yml 的 vite service 跑（node:22-alpine，掛載 .:/var/www/html，用 flock 串行 npm install 後執行 npm run dev，對外 5173）。vite.config.js 的 server 需維持 host: '0.0.0.0' + hmr.host: 'localhost' + strictPort，public/hot 才會寫成 http://localhost:5173（瀏覽器直連 vite，nginx 不需代理）。正式環境無 vite service：Dockerfile.prod 的 assets stage 先 npm run build 再 bake 進 runtime，資產來源以映像為準。

## memcached 不可用 -m 0（會崩潰），用 512 當惰性上限
memcached 1.6 不接受 -m 0：啟動直接跳 "Cannot set item size limit higher than 1/2 of memory max" 並退出，容器會一直 Restarting。memcached 沒有無限記憶體模式，-m 單位是 MB，且是惰性上限（有存資料才吃記憶體），dev 用 512 即可。不要改回 -m 0。
