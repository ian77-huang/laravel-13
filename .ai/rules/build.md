---
paths:
  - 'public/build/**'
---

# Build

## Octane 下 build 後� 須重啟 worker，否則 manifest 是舊的
App 跑在 Octane (Swoole)，Vite::manifest() 用 static::$manifests 快取 manifest，worker 只讀一次。跑 npm run build 之後，部分 worker 仍吐舊的 hashed asset URL（404 → 整頁沒樣式），nginx 輪詢到就會「重整一次有樣式一次沒有」。每次 build 完必須重啟 app 容器：docker compose -p my-new-laravel restart app1 app2 app3。
