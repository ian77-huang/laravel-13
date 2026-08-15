---
paths:
  - 'lang/**'
---

# Lang

## Use zh_TW (underscore) locale, never zh-TW
Locales use the underscore form zh_TW everywhere (app.locale, available_locales, lang/zh_TW/ dirs, bible language column, locale-switcher). Never use hyphenated zh-TW — it breaks Laravel lang dir lookup and Filament zh_TW translations.
