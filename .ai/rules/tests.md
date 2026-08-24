---
paths:
  - 'tests/**'
  - 'tests/**/*.php'
---

# Tests

## Tests need CONCURRENCY_DRIVER=sync for in-memory SQLite
Laravel's default concurrency driver is "process", which runs closures in a child PHP process. In tests with sqlite :memory:, the child process has an empty database (no tables/data), so any query inside Concurrency::run fails with a serialization TypeError. phpunit.xml sets <env name="CONCURRENCY_DRIVER" value="sync"/> so closures run inline.

## Migrations seed 10 demo users (user1~user10)
database/migrations/2026_08_17_191403_seed_users_table.php creates demo users user1@test.com ~ user10@test.com during migrate. With RefreshDatabase (sqlite :memory:), every test starts with these 10 users already present. Never assert absolute User::counts in tests — assert relative to factory-created users instead. Also: unauthenticated requests to routes under the /api prefix return 401 JSON (not a login redirect), even for web-session auth.

## Event 測試：assertDispatched 而非 assertPushed；PrivateChannel name 含 private- 前綴
測 Event 廣播/事件的陷阱：(1) Event facade 用 assertDispatched/assertDispatchedTimes/assertNothingDispatched——assertPushed 是 Queue/Broadcast 的方法，EventFake 沒有會經 __call 轉發到底層 Dispatcher，報出誤導的「Method Illuminate\Events\Dispatcher::assertPushed does not exist」。(2) EventFake 的「The expected [X] event was not dispatched」訊息在「回呼條件不匹配」時也會拋出，不代表事件沒發。(3) PrivateChannel('foo') 的 ->name 是 'private-foo'（建構子自動加前綴），斷言頻道名要含前綴。
