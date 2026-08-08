# My New Laravel

Laravel 13 + Laravel Octane (Swoole) 專案，以 Docker 建置正式與測試兩種環境。

## 環境需求

- Docker Desktop
- Composer（本機，用於 tinker/測試等本機工具）

> 本機不需安裝 PHP / Nginx / MySQL / Redis —— 全部都在 Docker 容器內。

## 架構

```
瀏覽器 → nginx:8000 → app (Octane + Swoole):8000 → memcached / mysql / redis
```

- **nginx**：入口，伺服靜態資源並 proxy 到 Octane。
- **app**：Laravel + Octane（Swoole），程式常駐記憶體、支援真正並行抓取。
- **memcached**：Cache 儲存（`CACHE_STORE=memcached`）。
- **mysql / redis**：僅測試環境有，正式環境改用外部連線。

## 測試環境

測試環境 = 基底 + mysql/redis 容器 + Octane `--watch`（改 code 自動熱更新）。

```bash
docker compose up -d
```

啟動後網站在 http://localhost:8000 。

#### 常用指令

```bash
docker compose up -d     # 啟動
docker compose logs -f app   # 看 app（Octane）日誌
docker compose down      # 停止（保留 docker/mysql_data、docker/redis_data 資料）
docker compose down -v   # 停止並刪除資料
```

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

正式環境**不含** mysql/redis 容器，DB 與 Redis 使用外部服務連線；Octane 不開 `--watch`、`APP_ENV=production`。

```bash
cp .env.prod.example .env.prod   # 填入外部 DB/Redis 位址
docker compose -f docker-compose.yml -f docker-compose.prod.yml --env-file .env.prod up -d --build
```

## Docker 設定檔說明

專案使用三份 compose 設定檔，透過「疊加」機制組合成不同環境：

| 檔案 | 用途 | 啟動時是否自動套用 |
|---|---|---|
| `docker-compose.yml` | 基底：共享服務（app / nginx / memcached） | 是 |
| `docker-compose.override.yml` | 測試：基底 + mysql/redis + `--watch` | 是（預設自動合併） |
| `docker-compose.prod.yml` | 正式：無 mysql/redis、外部連線、production 模式 | 否（需用 `-f` 指定） |

### docker-compose.yml（基底 — 兩環境共享）

- `memcached`：快取服務，`memcached:1.6-alpine`，本機 port 11211。`healthcheck` 用 `nc` 檢查（原 `memcached-tool` 不存在）。
- `app`：由 `docker/php/Dockerfile` 自行 build（PHP 8.4-cli + Swoole + memcached/pdo_mysql/sockets + opcache）。`volumes .:/var/www/html` 把專案掛載進容器。此層不設定 `DB_HOST` / `REDIS_HOST`，留給各環境填入。
- `nginx`：入口，`8000:80`，掛載 `public/`（唯讀）與 `docker/nginx/default.conf`。

### docker-compose.override.yml（測試環境）

`docker compose up` 會自動把此檔合併進基底，不需手動指定。它覆寫：

- `mysql`：本機 port 3307，資料持久化於 `docker/mysql_data`。
- `redis`：本機 port 6380，`appendonly yes` 持久化。
- `app`：填 `DB_HOST: mysql`、`REDIS_HOST: redis`（容器內用**服務名**互連）、`APP_ENV: local`、`APP_DEBUG: true`，並在 `command` 加入 `--watch` 啟用熱更新。

### docker-compose.prod.yml（正式環境）

不自動套用，需用 `-f` 指定並搭配 `.env.prod`：

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml --env-file .env.prod up -d
```

它覆寫 `app`：

- 移除 mysql/redis 容器，`DB_HOST` / `REDIS_HOST` 改讀 `.env.prod` 的外部位址。
- `APP_ENV: production`、`APP_DEBUG: false`。
- `command`：`composer install --no-dev --optimize-autoloader` + `npm run build` + Octane 啟動（無 `--watch`）。

## 本機常用指令

```bash
php artisan test --compact        # 執行測試
php artisan tinker                # 本機互動式除錯
vendor/bin/pint --dirty           # 格式化 PHP 程式碼
```

> 注意：`tinker` / `test` 等 CLI 指令在本機跑（非容器內），但容器內服務仍是主要執行環境。
