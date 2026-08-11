---
paths:
  - 'resources/views/**'
---

# Views

## daisyUI card/btn/menu 會自訂字級，蓋過 16px 基礎
html/body 未設 font-size 時基礎是 16px，但 daisyUI 元件會自訂 font-size：.card-body 用 var(--card-fs)，card-md=14px、card-sm=12px、card-xs=11px；.btn=14px、.menu 約 12-14px、.card-title=18px。沒掛字級 class 的文字放在 card-body 內會小於 16px。
