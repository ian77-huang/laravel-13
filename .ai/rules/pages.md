---
paths:
  - 'app/Filament/Admin/Resources/**/Pages/*List*.php'
---

# Pages

## Custom ListRecords pages use the $transKeys breadcrumb property
App\Filament\Custom\Records\ListRecords exposes `protected static array $transKeys = ['breadcrumb' => null]`. Every resource List page extending it should override `$transKeys` with its breadcrumb translation key (e.g. `['breadcrumb' => 'user.user']`).
