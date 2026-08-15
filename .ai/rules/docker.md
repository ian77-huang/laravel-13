---
paths:
  - 'docker/**'
---

# Docker

## Dev OPcache must validate timestamps
Dev and prod use separate OPcache configs. Dev image (docker/php/Dockerfile) copies docker/php/opcache.dev.ini with validate_timestamps=1 + revalidate_freq=0 so code changes apply immediately; prod (Dockerfile.prod) copies opcache.ini with validate_timestamps=0 for performance. Always edit the dev file, never change the shared opcache.ini to dev-style settings, or prod loses its caching.
