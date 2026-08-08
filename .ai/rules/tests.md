---
paths:
  - 'tests/**'
---

# Tests

## Tests need CONCURRENCY_DRIVER=sync for in-memory SQLite
Laravel's default concurrency driver is "process", which runs closures in a child PHP process. In tests with sqlite :memory:, the child process has an empty database (no tables/data), so any query inside Concurrency::run fails with a serialization TypeError. phpunit.xml sets <env name="CONCURRENCY_DRIVER" value="sync"/> so closures run inline.
