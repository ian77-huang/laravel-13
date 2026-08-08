# My New Laravel

## 環境需求

- PHP 8.4
- Docker Desktop（提供 MySQL 8.4 與 Redis 7）
- Composer

## 專案設定

### 1. 啟動 docker

啟動 MySQL 與 Redis 容器（資料保存在 `docker/mysql_data`、`docker/redis_data`，刪除容器不會遺失）：

```bash
docker compose up -d
```

#### 常用指令

```bash
docker compose up -d    # 啟動容器
docker compose down     # 停止容器（保留資料）
docker compose down -v  # 停止並刪除 docker/mysql_data、docker/redis_data 資料
php artisan test --compact  # 執行測試
```

### 2. 同步資料庫

確認 `.env` 設定正確（對外連接埠為 mysql -> `3307`、 redis -> `6380`）：

```bash
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=my_new_laravel
DB_USERNAME=root
DB_PASSWORD=root

REDIS_HOST=127.0.0.1
REDIS_PORT=6380
```

執行 migration 建立資料表：

```bash
php artisan migrate
```

如需填入測試資料：

```bash
php artisan db:seed
```

### 2. 安裝前端依賴並啟動

```bash
npm install
npm run dev
```

#### 常用指令

```bash
php artisan test --compact  # 執行測試
```
