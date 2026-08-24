---
paths:
  - 'resources/views/**'
  - 'resources/views/**/*.blade.php'
---

# Views

## daisyUI card/btn/menu 會自訂字級，蓋過 16px 基礎
html/body 未設 font-size 時基礎是 16px，但 daisyUI �
�件會自訂 font-size：.card-body 用 var(--card-fs)，card-md=14px、card-sm=12px、card-xs=11px；.btn=14px、.menu 約 12-14px、.card-title=18px。沒掛字級 class 的文字放在 card-body �
�會小於 16px。

## daisyUI 卡片等高：用 mt-auto 推底，勿用巢狀 height:100%
讓 daisyUI 卡片�
�容等高：不要用巢狀百分比高度鏈（如 .grid �
�再 height:100%），瀏覽器會把 flex 子層的百分比當 indefinite 而失效。做法：a 用 flex h-full、x-card 加 class="h-full"，卡片�
�容直接放在 card-body（本身已是 flex flex-col）裡，最後一行用 mt-auto 推到最底。等高由 grid align-items:stretch 保證。

## Alpine dropdown：click.outside 綁根� �素、顯示交給 dropdown-open class
daisyUI 5 dropdown + Alpine 的正確寫法：根元素放 x-data 與 @click.outside，用 :class="open && 'dropdown-open'" 控制顯示。陷阱：(1) @click.outside 綁在下拉內容元素上時，點擊觸發按鈕（在內容元素之外）會在同一個 click 事件內立刻把 open 設回 false，選單永遠打不開——outside 是相對於帶指令的元素本身，不是 x-data 根。(2) 不要用 tabindex+focus 依賴 daisyUI 的 :focus-within 機制再疊加 x-show，兩套機制會互相打架（且開啟時 daisyUI 會給 [tabindex]:first-child 設 pointer-events:none）。
