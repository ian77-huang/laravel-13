---
paths:
  - 'resources/css/**'
---

# Css

## daisyUI � �件樣式壓過 Tailwind utility，需未分層規則或 !important
daisyUI 5 + Tailwind v4：daisyUI 元件樣式（如 .card-body 的 font-size:var(--card-fs)）編譯後被包在晚宣告的子層（@layer utilities > daisyui.l1.l2.l3）裡，優先權高於 Tailwind utility（如 .text-base）。utility 壓不過去。要覆寫請在 app.css 最下方寫未分層規則，或使用 ! 前綴（!text-base）。
