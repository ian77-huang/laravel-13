# My New Laravel

Laravel 13 + Laravel Octane (Swoole) 專案，以 Docker 建置正式與測試兩種環境。

## 環境需求

- Docker Desktop
- Composer（本機，用於 tinker/測試等本機工具）

> 本機不需安裝 PHP / Nginx / MySQL / Redis —— 全部都在 Docker 容器內。

## 架構

```
瀏覽器 → nginx:8000 → app1/app2/app3 (Octane + Swoole):8000 → memcached / mysql / redis
```

- **nginx**：入口，伺服靜態資源並以 `upstream` 對 3 台 app **round-robin 負載平衡**（含 keepalive）。
- **app1 / app2 / app3**：Laravel + Octane（Swoole）三副本，程式常駐記憶體、支援真正並行抓取。
- **memcached**：Cache 儲存（`CACHE_STORE=memcached`），三台 app 共用。
- **mysql / redis**：僅測試環境有，正式環境改用外部連線。

## 測試環境

測試環境 = 基底 + mysql/redis 容器 + 3 台 app（Octane `--watch` 改 code 自動熱更新）。

```bash
docker compose up -d
```

啟動後網站在 http://localhost:8000 。

#### 常用指令

```bash
docker compose up -d     # 啟動（含 3 台 app 的首次 setup）
docker compose logs -f app1 app2 app3   # 看 app（Octane）日誌
docker compose exec nginx nginx -s reload   # 重要！app 重建後重新解析 IP（見下方注意）
docker compose down      # 停止（保留 docker/mysql_data、docker/redis_data 資料）
docker compose down -v   # 停止並刪除資料
```

> **重要**：nginx 的 `upstream` 是**靜態**解析——只在 nginx 啟動/reload 時解析一次 `app1/2/3` 的 IP。若 app 容器重建換了 IP（如 `docker compose up -d --build`），必須執行 `docker compose exec nginx nginx -s reload`，否則請求會打到舊 IP 而 502。

**測試環境禁止 HTTP 快取**：`PreventHttpCache` middleware 只在本機環境（`APP_ENV=local`）對所有 response 送出 `Cache-Control: no-store`，確保每次重新整理都拿到新頁面（驗證負載平衡時 footer 的 `ServerName(...)` 會跳動）。正式環境不受影響。

本機連資料庫測試用 `3307`(mysql) / `6380`(redis)：

```bash
DB_HOST=127.0.0.1
DB_PORT=3307
REDIS_HOST=127.0.0.1
REDIS_PORT=6380
```

### 資料庫遷移與種子

確認 `.env` 中連線設定正確後，執行 migration 建立資料表：

```bash
php artisan migrate
```

如需填入測試資料（經文等）：

```bash
php artisan db:seed
```

> 本機 CLI 透過 `127.0.0.1:3307` 連到容器內 MySQL。

### Octane 特性

- **熱更新**：改 code 後 log 會出現 `Application change detected. Restarting workers…`，瀏覽器重新整理即可看到。
- **真並行**：在 web request 內用 `Octane::concurrently([...])` 並行執行多個任務（Swoole task workers），效果等同 goroutine：

```php
use Laravel\Octane\Facades\Octane;

[$users, $servers] = Octane::concurrently([
    fn () => User::all(),
    fn () => Server::all(),
]);
```

## 正式環境

正式環境**不含** mysql/redis 容器，DB 與 Redis 使用外部服務連線；3 台 app 不開 `--watch`、`APP_ENV=production`。

```bash
cp .env.prod.example .env.prod   # 填入外部 DB/Redis 位址
docker compose -f docker-compose.yml -f docker-compose.prod.yml --env-file .env.prod up -d --build
```

## Docker 設定檔說明

專案使用三份 compose 設定檔，透過「疊加」機制組合成不同環境：

| 檔案 | 用途 | 啟動時是否自動套用 |
|---|---|---|
| `docker-compose.yml` | 基底：共享服務（3×app / nginx / memcached） | 是 |
| `docker-compose.override.yml` | 測試：基底 + mysql/redis + `--watch` | 是（預設自動合併） |
| `docker-compose.prod.yml` | 正式：無 mysql/redis、外部連線、production 模式 | 否（需用 `-f` 指定） |

### docker-compose.yml（基底 — 兩環境共享）

- `memcached`：快取服務，`memcached:1.6-alpine`，本機 port 11211。`healthcheck` 用 `nc` 檢查（原 `memcached-tool` 不存在）。
- `app1 / app2 / app3`：由 `docker/php/Dockerfile` 自行 build（PHP 8.4-cli + Swoole + memcached/pdo_mysql/sockets + opcache）。三台共用同一個 `volumes .:/var/www/html` 掛載，靠 `x-app` YAML anchor 去重複定義。內建 TCP healthcheck（探測 8000），讓 nginx 等三台就緒才啟動。此層不設定 `DB_HOST` / `REDIS_HOST`，留給各環境填入。
- `nginx`：入口，`8000:80`，掛載 `public/`（唯讀）與 `docker/nginx/default.conf`。`depends_on` 三台 app 的 `service_healthy`，避免 Octane 未就緒時的初啟 502。

### docker-compose.override.yml（測試環境）

`docker compose up` 會自動把此檔合併進基底，不需手動指定。它覆寫：

- `mysql`：本機 port 3307，資料持久化於 `docker/mysql_data`。
- `redis`：本機 port 6380，`appendonly yes` 持久化。
- `app1/2/3`：填 `DB_HOST: mysql`、`REDIS_HOST: redis`（容器內用**服務名**互連）、`APP_ENV: local`、`APP_DEBUG: true`，`command` 用 `flock /var/www/html/.setup.lock` 串行化三台的 `composer install` / `npm install`（避免同時寫入共享掛載的 vendor/node_modules），最後 Octane `--watch` 啟動。

### docker-compose.prod.yml（正式環境）

不自動套用，需用 `-f` 指定並搭配 `.env.prod`：

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml --env-file .env.prod up -d
```

它覆寫 `app1/2/3`：

- 移除 mysql/redis 容器，`DB_HOST` / `REDIS_HOST` 改讀 `.env.prod` 的外部位址。
- `APP_ENV: production`、`APP_DEBUG: false`。
- `command`：`flock` 串行化 `composer install --no-dev --optimize-autoloader`、`npm install`、`npm run build`，最後 Octane 啟動（無 `--watch`）。

### nginx 負載平衡（docker/nginx/default.conf）

```nginx
upstream octane_backend {
    server app1:8000;
    server app2:8000;
    server app3:8000;
    keepalive 32;
}
```

- 預設 round-robin，三台自動輪流。
- `keepalive 32` 讓 nginx 對三台各保持長連線（需配合 `proxy_http_version 1.1`）。
- 靜態 upstream 的限制與 reload 步驟見「測試環境」的注意事項。

## 本機常用指令

```bash
php artisan test --compact        # 執行測試
php artisan tinker                # 本機互動式除錯
vendor/bin/pint --dirty           # 格式化 PHP 程式碼
```

> 注意：`tinker` / `test` 等 CLI 指令在本機跑（非容器內），但容器內服務仍是主要執行環境。
