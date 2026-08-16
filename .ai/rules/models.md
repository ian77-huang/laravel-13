---
paths:
  - 'app/Models/Youtube*.php'
  - 'app/Models/*.php'
---

# Models

## YouTube 領域沿用來源 camelCase 欄位
Youtube 相關表（youtube_channels、youtube_playlists、youtube_playlist_items、youtube_videos、youtube_playlist_videos）的欄位刻意沿用來源 Postgres schema 的 camelCase（channelId、publishedAt、playlistId），非 Laravel snake_case。所有這些表 id 都是 string primary key（incrementing=false）。youtube_playlist_videos 是 playlist↔video 的 join 表。

## User implements FilamentUser (canAccessPanel via is_admin)
User model implements FilamentUser and canAccessPanel() returns is_admin. Without it, Filament's Authenticate middleware aborts 403 unless app.env==='local', so ALL /admin routes fail in 'testing' (and everyone is blocked in prod). If you remove FilamentUser or the is_admin rule, tests touching /admin and prod admin access break.
