# Contributing

Read `AGENTS.md`, `CONTEXT.md`, `planning/README.md`, `planning/CONVENTIONS.md`, and the focused local planning rules before proposing a
change. New work follows accepted EPIC outcomes, requirement TICKETs, then bounded TASKs, normally one PR each.
Record execution authority and checkout/worktree choice, preserve existing branch/legacy-record identities, and
keep the change to one vertical slice. During bootstrap only TASK-00001 may execute under its explicit exception.
Update capability documentation with behavior, run `./bin/planning-check --write` then `./bin/planning-check` for
planning changes, and run `./bin/build` before requesting independent Spec and Standards review. The implementer
cannot accept their own work; record pending review honestly instead of marking the TASK done prematurely.
The sole execution Board remains `planning/tickets/BOARD.md`; publication and merge require separate authority.

For a fresh checkout, run `./bin/composer install`. The project runs Composer and PHPUnit in its pinned Docker
image, so local PHP extensions and host Composer versions do not become an unrecorded prerequisite.

Tool caches belong under `var/cache/<tool>` (for example, PHPUnit uses `var/cache/phpunit`). Do not configure
root-level cache files; `var/` is the single ignored runtime and cache root.

Do not duplicate Fight Common or Fight AccessControl Domain/Application code. Changes to public shared behavior
belong in the owning package; Symfony composition and adapters belong here.
