---
paths:
  - 'database/migrations/*bible*'
---

# Migrations

## Bible data source & structure
bible_* tables (bible_books, bible_verse_refs, bible_verses) use snake_case. Language is a single `language` column (zh_TW/en) on bible_books and bible_verses. bible_verse_refs holds canonical (abbreviation, chapter, verse) identity shared across languages; bible_verses.verse_ref_id FKs to it. Seeder JSON in database/seeders/data/bible_*.json is generated from getbible.net v2 API (cut.json = 和合本繁體 zh_TW, kjv.json = KJV en; chapters/verses arrays are 0-based). Books stay fixed at 66 books x 2 languages.
