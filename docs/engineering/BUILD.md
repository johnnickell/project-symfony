# Canonical verification

`./bin/build` is the sole complete local gate and the command used by hosted CI. Focused wrappers shorten
iteration; they do not replace it. A successful gate is not independent Review, QA, publication or merge.

## Preparation and isolation

Use repository-owned wrappers, not host PHP/Composer or a sibling vendor directory:

```sh
./bin/prepare
./bin/doctor                       # Readiness only; no install or rebuild
./bin/phpunit --testsuite Unit     # Focused iteration
./bin/build
```

Prerequisites: Docker with Compose v2 supporting `up --wait`, Bash, host Python 3.9+ and Git. Host PHP/Composer
are not required. `./bin/prepare` runs these explicit, noninteractive steps, stopping at the first failure:

1. `./bin/up`: inspect ownership and HTTP port, pull/build the pinned FPM base, build Nginx and recreate the selected
   services. Wait for the FPM master-process healthcheck before starting the server.
2. `./bin/composer install --no-interaction --prefer-dist --no-progress`: install the accepted lock, including dev
   tools and allowed plugins, in this checkout. Never substitute `update` or another checkout's `vendor/`.
3. `./bin/doctor`: check actual CLI/FPM PHP 8.5.12, session upload-progress Off in both, required extensions, active
   Xdebug coverage mode, installed autoload and real Composer platform requirements. This is capability evidence,
   not a Unit coverage verdict; run real Unit tests for that.

For separate steps use the same commands; `./bin/up --logs` follows logs. The PHP service is `api`; `./bin/exec`
executes there. `./bin/down` stops only the selected stack and preserves volumes. All wrappers resolve their own
checkout, use its Compose file and refuse existing project containers whose Compose working directory or `/app`
bind belongs elsewhere. Networks must have matching checkout ownership (legacy unlabelled networks require
matching containers), with no foreign attached containers. Startup refuses an occupied HTTP port before building
or replacing containers; Docker remains the final authority if a concurrent process takes the port afterward.
Inspect `docker compose config`, `docker compose ps -a` and `docker compose images` before runtime changes.
Do not bypass a guard, remove an ambiguous resource or stop another checkout's stack.

Linked worktrees require an explicit unique project name. Choose a free port and export both for the entire
session, including focused wrappers and shutdown; main checkouts may use the defaults `project-symfony`/18083.
Create a checkout with ordinary readable source permissions (for example, `umask 022` before `git worktree add`):
Nginx workers need to traverse the checkout and read `public/`. Do not broadly chmod an existing private tree.
For example, inside the intended linked worktree, after inspecting project/port ownership:

```sh
export COMPOSE_PROJECT_NAME=project-symfony-my-task
export HTTP_PORT=28083
./bin/prepare
./bin/exec php -v
./bin/phpunit --testsuite Unit --fail-on-skipped --fail-on-notice --fail-on-warning
./bin/coverage
./bin/down
```

Each checkout binds only its own root at `/app`. Its `vendor/`, `var/cache/<tool>` (including Composer and PHPUnit)
and `var/reports/` remain checkout-specific; wrappers reject symlinked mutable roots and mutable-tree symlinks
escaping the checkout. Do not share these directories or run concurrent writers within one checkout.
Composer's explicit `COMPOSER_ROOT_VERSION=dev-develop` avoids root-version Git discovery: a linked worktree's
host `.git` pointer need not resolve in the container. Git/planning commands stay on the host; never mount the
common Git directory or add wildcard safe-directory trust to make them work inside the service. The image's
existing safe-directory entry remains scoped to `/app` only. No database, worker, browser or full WF-002 topology
is provisioned here.

### Failure diagnosis

- Missing/running-service errors: inspect the selected project with `docker compose ps -a` and logs, then run
  `./bin/prepare`. `./bin/exec`/`./bin/doctor` do not silently start services.
- Project/mount/network refusal: inspect ownership and choose a unique `COMPOSE_PROJECT_NAME`; retain ambiguous
  resources for their owner. Port refusal: select a free `HTTP_PORT`, not someone else's shutdown command.
- Pinned-base/pull/build failure: restore registry/network access and retry explicit preparation. Never remove the
  digest or fall back to an older PHP image. FPM health failure: inspect `docker compose logs api`.
- HTTP permission errors: inspect checkout directory/public-file permissions for Nginx workers. Recreate an owned
  disposable checkout with ordinary permissions rather than changing private/shared source permissions blindly.
- Composer/install/platform failure: inspect the first failing package/extension or filesystem permission. Preserve
  `composer.lock`; do not use update, ignore-platform flags or sibling dependencies as recovery.
- Coverage capability failure: rebuild with `./bin/up`, ensure `XDEBUG_MODE=coverage`, then rerun `./bin/doctor`.
  Diagnose Unit failures with the real focused command and retain its report before another suite overwrites it.

## Unchanged canonical gate

The current `bin/build` first validates planning on the host, builds `etc/docker/build/Dockerfile`, then runs a
one-off container against this checkout. It validates Composer and installs the lock, lints PHP, runs the installed
Fight Common PHPCS standard, PHPStan, Deptrac (including uncovered/unassigned checks), Rector dry-run, Unit tests
with exact coverage validation, then Integration and Functional suites. Each suite runs once. Retain all phases,
warning/skip failure flags and the full Unit-only requirement.

TASK-00005 supplies explicit reusable-service preparation only. TASK-00006 owns switching this gate and hosted
setup to named fail-fast PHP phases in the running service, and durable Unit evidence. The current gate still
rebuilds an ephemeral image and installs dependencies on every invocation; preparation does not remove those
mechanics. Hosted setup is unchanged in this slice.

## Coverage evidence

PHPUnit uses Xdebug in the build image, `requireCoverageMetadata`, and production `src/` excluding only the Kernel.
`scripts/check-coverage.php` requires exact covered/total statement equality and rejects production ignore
annotations, missing/malformed reports and contradictory metrics. Retain those checks and meaningful assertions.

Current suites share `var/reports/coverage/clover.xml`; later Integration/Functional runs can overwrite it. The
canonical log's Unit coverage decision is attributable to the Unit run, but the final XML is not a durable Unit
artifact. For inspection, retain a distinct copy from a focused Unit run before another suite executes; label
that focused evidence accurately. T-00010 owns fixing artifact persistence in the canonical gate. Never represent
a combined suite percentage as Unit coverage or manufacture tests/exclusions to obtain 100%.

## Long noninteractive gate

Use the detached protocol in root AGENTS.md, choosing a fresh task/run-specific screen name and temporary paths:

```sh
screen -dmS <task>-build /bin/zsh -lc './bin/build > /private/tmp/<task>-build.log 2>&1; print -r -- $? > /private/tmp/<task>-build.exit'
```

These are placeholders: substitute a unique owned run before execution. Confirm no previous exit file can be
mistaken for this run. Inspect progress/logs and require the exit file to contain `0`; a foreground timeout, empty
log or live process is not a build result. Retain owned logs/exits under `.runs/tasks/<TASK-ID>/runs/<run-id>/`.
Do not stop unrelated sessions or treat interrupted builds as success. Avoid concurrent gates/cache/report users.

Before commit/PR, refresh authorized planning views with `./bin/planning-check --write`, run the read-only checker,
`git diff --check`, and the full gate on the final tree. Inspect owned diffs, counts, Unit denominator/driver,
warnings, omissions and source stability; do not hide unexpected lock changes from installation.

Hosted CI delegates to this same gate. Local and hosted results are distinct. Preserve explicit clean-clone and
exact-head hosted requirements in accepted scope; workflow presence alone adds no new acceptance requirement.
Report the actual tested commit and source-head/merge-candidate relationship, not merely a green branch name.
