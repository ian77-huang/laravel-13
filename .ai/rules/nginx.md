---
paths:
  - 'docker/nginx/**'
---

# Nginx

## nginx upstream 靜態解析：app 重建後必須 reload
nginx 的 `upstream octane_backend`（server app1/2/3:8000）只在 nginx 啟動/reload 時解析一次容器 IP。任何 app 容器重建換 IP 後（docker compose up -d --build），必須跑 `docker compose exec nginx nginx -s reload`，否則 round-robin 打向舊 IP 會大量 502。所有 app 的 command 需用 `flock /var/www/html/.setup.lock` 串行化 composer/npm 安裝，避免三台同時寫入共享的 /var/www/html 掛載。

## app 需 TCP healthcheck，nginx 等 service_healthy
`x-app` anchor 內建 TCP healthcheck（`cat < /dev/null > /dev/tcp/127.0.0.1/8000`，start_period 45s）。nginx 的 depends_on 必須用 `condition: service_healthy` 等三台 Octane 就緒才啟動，否則初次啟動時 app 還在跑 composer install，nginx 先起會短暫 502。
