# My New Laravel

Laravel 13 + Laravel Octane (Swoole) 專案，以 Docker 建置正式與測試兩種環境。
前端使用 Tailwind CSS v4 + daisyUI 5 + Alpine.js 3，以 Vite 打包。

## 目前網站狀態

首頁（`/`）目前包含兩個區塊：

- **每日經文**：`BibleService::findVerseByToday('zh_TW')` 以日期為種子隨機挑選一節經文，快取至當天午夜。
- **YouTube 頻道**：`YoutubeService::getChannels()` 隨機取 6 個啟用頻道，以卡片網格呈現；圖片經 `x-img` 元件（`x-lazy` directive）使用 IntersectionObserver 延遲載入。

### 功能特色

- **多語系切換**：`zh_TW` / `en`，透過 navbar 的 `x-locale-switcher`（Alpine `localeSwitcher` component）呼叫 `POST /api/lang`，`SetLocale` middleware 依 `locale` cookie 套用白名單語系（`config/app.php` 的 `available_locales`）。
- **Blade 元件**：自訂 `x-card`、`x-fieldset`、`x-img`、`x-icon.*`（brands/solid）、`x-locale-switcher`、`x-layouts.mc`、`x-layouts.*`。
- **快取**：memcached（`CACHE_STORE=memcached`），`CacheHub` 封裝，cache key 集中於 `config/constants.php`。
- **Octane 並行**：`IndexController` 以 `Octane::concurrently()` 同時抓取經文與頻道。

### 路由

| Method | URI | 說明 |
|---|---|---|
| `GET` | `/` | 首頁（每日經文 + YouTube 頻道） |
| `POST` | `/api/lang` | 切換語系（session + cookie） |

### 資料庫

- `bible_*`：聖經（`bible_books`、`bible_verse_refs`、`bible_verses`，多語經文）。
- `youtube_*`：YouTube 頻道 / 播放清單 / 影片資料。
- `roles`、`permissions`、`model_has_roles`、`model_has_permissions`、`role_has_permissions`：Spatie Permission 多角色權限管理。
- Seeder：`php artisan db:seed` 填入測試資料（經文、頻道等）。

## Filament 管理後台

使用 Filament v5 建立管理後台，路由位於 `/admin`。

### 權限管理系統

基於 Spatie Laravel Permission 實作多 guard 權限管理：

- **Guard**：`web`、`admin`、`api`
- **Modules**：`users`、`roles`（定義於 `config/permissions.php`）
- **Actions**：`view`、`create`、`edit`、`delete`

#### 權限頁面（PermissionsUser）

位置：`app/Filament/Admin/Resources/Users/Pages/PermissionsUser.php`

- 使用 `Filament\Schemas\Components\Fieldset` 分組 roles 和 permissions
- 每個 guard 獨立一個 Section，內含 `CheckboxList` 供勾選
- `mutateFormDataBeforeFill`：從 DB 讀取使用者的角色和權限，按 guard 分組填入表單
- `mutateFormDataBeforeSave`：透過 `PermissionService` 驗證角色和權限是否存在於 DB
- `handleRecordUpdate`：使用 `Role::findByName()` 和 `Permission::findByName()` 取得 model 實例後執行 `syncRoles()` / `syncPermissions()`

#### PermissionService

位置：`app/Services/PermissionService.php`

- `getRoles()`：從 DB 取得所有角色，按 guard 分組
- `formatPermissionsByGuard()`：依 config 定義的 guards 和 modules 格式化權限結構
- `formatPermissionsByModule()`：將使用者權限依 module 分組
- `formatPermissionsForForm()`：將使用者權限按 guard 分組，供表單填入
- `validRoles()`：驗證角色是否存在於對應 guard 的 DB 中，不存在則丟 `Halt` 異常
- `validPermissions()`：驗證權限是否符合 config 定義，不存在則丟 `Halt` 異常
- `validUserData()`：整合角色和權限驗證，保留其他表單欄位

#### 刪除使用者時的關聯清理

Spatie `HasRoles` trait 內建 `bootHasRoles()` 和 `bootHasPermissions()`，在 `deleting` 事件中自動執行 `detach()`，清除 `model_has_roles` 和 `model_has_permissions` 的關聯資料，無需額外處理。

#### 自訂 Blade 元件

- `resources/views/filament/components/title.blade.php`：標題元件（使用行內 style，因 Filament 面板未載入自訂 Tailwind CSS）
- `resources/views/filament/components/divider.blade.php`：分隔線元件（使用行內 style）

> **注意**：Filament 面板使用自己的 CSS 編譯流程，不會載入 `resources/css/app.css`。若需在 Filament 面板中使用自訂 Tailwind utility classes，需透過 `php artisan make:filament-theme` 建立自訂主題。

## 環境需求

- Docker Desktop
- Composer（本機，用於 tinker/測試等本機工具）
- Node.js（本機，用於 `npm run dev` / `npm run build`）

> 本機不需安裝 PHP / Nginx / MySQL / Redis —— 全部都在 Docker 容器內。

## 架構

```
瀏覽器 → nginx:8000 → app1/app2/app3 (Octane + Swoole):8000 → memcached / mysql / redis
```

- **nginx**：入口，伺服靜態資源並以 `upstream` 對 3 台 app **round-robin 負載平衡**（含 keepalive）。
- **app1 / app2 / app3**：Laravel + Octane（Swoole）三副本，程式常駐記憶體、支援真正並行抓取。
- **memcached**：Cache 儲存（`CACHE_STORE=memcached`），三台 app 共用。
- **mysql / redis**：僅開發環境有，正式環境改用外部連線。

## 開發環境

開發環境 = 3 台 app（Octane `--watch` 改 code 自動熱更新）+ nginx + memcached + mysql + redis，設定獨立於正式環境。

```bash
docker compose -f docker-compose.dev.yml up -d
```

啟動後網站在 http://localhost:8000 。

#### 常用指令

```bash
docker compose -f docker-compose.dev.yml up -d     # 啟動（含 3 台 app 的首次 setup）
docker compose -f docker-compose.dev.yml logs -f app1 app2 app3   # 看 app（Octane）日誌
docker compose -f docker-compose.dev.yml exec nginx nginx -s reload   # 重要！app 重建後重新解析 IP（見下方注意）
docker compose -f docker-compose.dev.yml down      # 停止（保留 docker/mysql_data、docker/redis_data 資料）
docker compose -f docker-compose.dev.yml down -v   # 停止並刪除資料
```

> **重要**：nginx 的 `upstream` 是**靜態**解析——只在 nginx 啟動/reload 時解析一次 `app1/2/3` 的 IP。若 app 容器重建換了 IP（如 `docker compose up -d --build`），必須執行 `docker compose exec nginx nginx -s reload`，否則請求會打到舊 IP 而 502。

**開發環境禁止 HTTP 快取**：`PreventHttpCache` middleware 只在本機環境（`APP_ENV=local`）對所有 response 送出 `Cache-Control: no-store`，確保每次重新整理都拿到新頁面（驗證負載平衡時 footer 的 `ServerName(...)` 會跳動）。正式環境不受影響。

本機連資料庫測試用 `3307`(mysql) / `6380`(redis)：

```bash
DB_HOST=127.0.0.1
DB_PORT=3307
REDIS_HOST=127.0.0.1
REDIS_PORT=6380
```

### 前端（Vite）

前端資源位於 `resources/`，由 Vite 打包（Tailwind v4 + daisyUI 5 + Alpine 3）：

```bash
npm run dev      # 開發模式（HMR）
npm run build    # 打包正式資源
```

Alpine 相關程式碼位於 `resources/js/`：`app.js` 註冊 `localeSwitcher` data component 與 `x-lazy` directive（`components/lazy-image.js`）。

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

正式環境**不含** mysql/redis 容器，DB 與 Redis 使用外部服務連線；3 台 app 不開 `--watch`、`APP_ENV=production`。程式碼在 build 時就**寫進映像**（bake），容器啟動只負責跑 Octane，不做任何安裝。

### 首次部署

```bash
cp .env.prod.example .env.prod   # 填入 APP_KEY、APP_URL、外部 DB/Redis 位址
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d --build
```

執行資料庫遷移：

```bash
docker compose -f docker-compose.prod.yml --env-file .env.prod exec app1 php artisan migrate --force
```

### 更新程式（重新部署）

正式環境的程式碼是 bake 進映像的，更新流程 = 重 build 映像 + 重建容器：

```bash
# 1. 在伺服器上拉最新程式碼
git pull

# 2. 重新 build 映像（內部會重跑 npm run build 與 composer install --no-dev）
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d --build

# 3. 有資料庫變更時執行遷移
docker compose -f docker-compose.prod.yml --env-file .env.prod exec app1 php artisan migrate --force

# 4. 重要！nginx 的 upstream 是靜態解析，app 重建後 IP 會變，必須 reload 一次
docker compose -f docker-compose.prod.yml --env-file .env.prod exec nginx nginx -s reload
```

> 若有新增/移除 npm 套件（`package.json` 變更），`docker compose ... build --no-cache` 或至少 build 時 assets stage 會因 `package-lock.json` 變更自動重建。

### 正式環境的靜態資源

正式環境沒有把本機 `public/` 掛載進容器。3 台 app 啟動時會把自己的 `public/` 複製到共享 volume `octane_public`，nginx 再以唯讀方式掛載該 volume 伺服靜態檔。此為名為 volume（非 bind mount），資料存於 Docker volume 中。

> 上傳檔案（`storage/app/public`）在正式環境**不會**出現在 nginx 的 volume 中，請改用雲端磁碟（如 S3）並將 `FILESYSTEM_DISK` 指向它，避免部署時被蓋掉。

## Docker 設定檔說明

開發與正式是**兩份完全獨立的 compose 設定檔**，不共用、不疊加。共用僅限於 `docker/php/opcache.ini` 與 `docker/nginx/` 設定：

| 檔案 | 用途 | 啟動指令 |
|---|---|---|
| `docker-compose.dev.yml` | 開發：app×3 + nginx + memcached + mysql + redis，volume 掛載 + `--watch` | `docker compose -f docker-compose.dev.yml up -d` |
| `docker-compose.prod.yml` | 正式：app×3 + nginx + memcached（無 mysql/redis），映像 bake 程式碼 | `docker compose -f docker-compose.prod.yml --env-file .env.prod up -d --build` |

### docker-compose.dev.yml（開發環境）

- `memcached`：快取服務，`memcached:1.6-alpine`，本機 port 11211。
- `mysql`：本機 port 3307，資料持久化於 `docker/mysql_data`（git 忽略）。
- `redis`：本機 port 6380，`appendonly yes` 持久化。
- `app1 / app2 / app3`：由 `docker/php/Dockerfile` build（PHP 8.4-cli + Swoole + memcached/pdo_mysql/sockets + opcache + node + composer）。三台共用同一個 `volumes .:/var/www/html` 掛載，靠 `x-app` YAML anchor 去重複定義。`command` 用 `flock /var/www/html/.setup.lock` 串行化三台的 `composer install` / `npm install`（避免同時寫入共享掛載的 vendor/node_modules），最後 Octane `--watch` 啟動。
- `nginx`：入口，`8000:80`，掛載本機 `public/`（唯讀）與 `docker/nginx/default.test.conf`。`depends_on` 三台 app 的 `service_healthy`，避免 Octane 未就緒時的初啟 502。

### docker-compose.prod.yml（正式環境）

需搭配 `.env.prod`（`--env-file` 指定）填入 `APP_KEY`、`APP_URL`、外部 DB/Redis 位址：

- 無 mysql/redis 容器，`DB_HOST` / `REDIS_HOST` 讀 `.env.prod` 的外部位址。
- `APP_ENV: production`、`APP_DEBUG: false`、`APP_KEY` 由環境變數提供（映像內無 `.env`）。
- `app1 / app2 / app3`：由 `docker/php/Dockerfile.prod`（多階段）build，映像內已含 `composer install --no-dev`、`npm run build` 後的完整程式碼與 assets。容器以非 root 的 `octane` 使用者執行，啟動時只做 `cp -r public/. → 共享 volume` 再跑 Octane。
- `nginx`：入口，掛載共享 volume `octane_public`（唯讀）與 `docker/nginx/default.conf`。

### Dockerfile 差異

- `docker/php/Dockerfile`（開發）：基底映像，含 node/composer 供開發時安裝相依；程式碼靠 volume 掛載。
- `docker/php/Dockerfile.prod`（正式）：多階段（base → assets → vendor → runtime），runtime 不含 node 與建置工具，映像自帶應用程式。

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
- 靜態 upstream 的限制與 reload 步驟見「開發環境」的注意事項。

## 本機常用指令

```bash
php artisan test --compact        # 執行測試
php artisan tinker                # 本機互動式除錯
vendor/bin/pint --dirty           # 格式化 PHP 程式碼
npm run dev                       # 前端開發（Vite HMR）
npm run build                     # 前端打包
```

> 注意：`tinker` / `test` 等 CLI 指令在本機跑（非容器內），但容器內服務仍是主要執行環境。
