---
paths:
  - 'resources/views/**'
---

# Views

## daisyUI card/btn/menu 會自訂字級，蓋過 16px 基礎
html/body 未設 font-size 時基礎是 16px，但 daisyUI 元件會自訂 font-size：.card-body 用 var(--card-fs)，card-md=14px、card-sm=12px、card-xs=11px；.btn=14px、.menu 約 12-14px、.card-title=18px。沒掛字級 class 的文字放在 card-body 內會小於 16px。

## daisyUI 卡片等高：用 mt-auto 推底，勿用巢狀 height:100%
讓 daisyUI 卡片內容等高：不要用巢狀百分比高度鏈（如 .grid 內再 height:100%），瀏覽器會把 flex 子層的百分比當 indefinite 而失效。做法：a 用 flex h-full、x-card 加 class="h-full"，卡片內容直接放在 card-body（本身已是 flex flex-col）裡，最後一行用 mt-auto 推到最底。等高由 grid align-items:stretch 保證。
