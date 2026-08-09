---
paths:
  - 'app/Support/**'
---

# Support

## Cache � 裝統一用 App\Support\Cache\Store
自訂工具類別放 app/Support/（namespace App\Support\...）。Cache 包裝用 App\Support\Cache\Store：建構子注入 Illuminate\Contracts\Cache\Repository 與 string $prefix，所有方法（get/put/remember/has/forget/flush）會自動加前綴避免 key 衝突。用容器注入取得實例。
