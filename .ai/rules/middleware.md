---
paths:
  - 'app/Http/Middleware/**'
---

# Middleware

## 測試環境禁 HTTP 快取：PreventHttpCache
`PreventHttpCache` middleware 註冊於 web group（bootstrap/app.php），只在 `app()->environment('local')` 時送出 `Cache-Control: no-store` 等 header，其餘環境直接放行。目的：測試環境避免瀏覽器快取遮蔽負載平衡驗證（Cmd+R 就能看到 ServerName 跳動）。判斷必須放在 middleware handle 內（request 階段），不可在 bootstrap/app.php 的 withMiddleware closure 用 app()->environment()——該階段容器未就緒會拋例外。
