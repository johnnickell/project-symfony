# Contributing

Read `AGENTS.md`, `CONTEXT.md`, `planning/README.md`, and the focused local planning rules before proposing a
change. Create or update a repository-local ticket, keep the change to one vertical slice, update capability
documentation with behavior, and run `./bin/build` before requesting review.

For a fresh checkout, run `./bin/composer install`. The project runs Composer and PHPUnit in its pinned Docker
image, so local PHP extensions and host Composer versions do not become an unrecorded prerequisite.

Tool caches belong under `var/cache/<tool>` (for example, PHPUnit uses `var/cache/phpunit`). Do not configure
root-level cache files; `var/` is the single ignored runtime and cache root.

Do not duplicate Fight Common or Fight AccessControl Domain/Application code. Changes to public shared behavior
belong in the owning package; Symfony composition and adapters belong here.
