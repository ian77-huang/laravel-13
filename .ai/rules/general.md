---
paths:
  - 'docker-compose*.yml'
---

# General

## 開發/正式 Docker 環境完� �分離
不要重製共用的 docker-compose.yml 基底。開發用 docker-compose.dev.yml（volume 掛載 + octane --watch 熱更新），正式用 docker-compose.prod.yml（映像 bake 程式碼，非 root octane 使用者，無 volume 掛載）。正式 nginx 靜態資源靠共享 volume octane_public：app 啟動時 cp public/. 到該 volume，nginx 唯讀掛載。新增正式環境變數（如 DB_HOST）時必須同時更新 .env.prod.example 與 docker-compose.prod.yml。
