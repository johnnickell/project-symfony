# Canonical verification

`./bin/build` is the sole complete local gate and the command used by hosted CI. Focused wrappers shorten
iteration; they do not replace it. A successful gate is not independent Review, QA, publication or merge.

## Preparation and isolation

Use repository-owned wrappers, not host PHP/Composer or a sibling vendor directory:

```sh
./bin/prepare
./bin/doctor                       # Readiness only; no install or rebuild
./bin/phpunit --testsuite Unit --coverage-clover var/reports/coverage/unit/clover.xml
./bin/coverage                     # Inspect that focused Unit report
./bin/build                        # Canonical run; no need for the focused pass first
```

Prerequisites: Docker with Compose v2 supporting `up --wait`, Bash, host Python 3.9+ and Git. Host PHP/Composer
are not required. `./bin/prepare` runs these explicit, noninteractive steps, stopping at the first failure:

1. `./bin/up`: inspect ownership and HTTP port, pull/build the pinned FPM base, build Nginx and recreate the selected
   services. Wait for the FPM master-process healthcheck before starting the server.
2. `./bin/composer install --no-interaction --prefer-dist --no-progress`: install the accepted lock, including dev
   tools and allowed plugins, in this checkout. Never substitute `update` or another checkout's `vendor/`.
3. `./bin/doctor`: check actual CLI/FPM PHP 8.5.12, session upload-progress Off in both, required extensions, active
   Xdebug coverage mode, installed autoload, installed versions/source/dist identities against the lock (including
   dev packages), and real Composer platform requirements. This is readiness evidence, not a Unit coverage verdict.

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
./bin/build
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
  `./bin/prepare`. The gate, `./bin/exec` and `./bin/doctor` never silently start services.
- Project/mount/network refusal: inspect ownership and choose a unique `COMPOSE_PROJECT_NAME`; retain ambiguous
  resources for their owner. Port refusal: select a free `HTTP_PORT`, not someone else's shutdown command.
- Pinned-base/pull/build failure: restore registry/network access and retry explicit preparation. Never remove the
  digest or fall back to an older PHP image. FPM health failure: inspect `docker compose logs api`.
- HTTP permission errors: inspect checkout directory/public-file permissions for Nginx workers. Recreate an owned
  disposable checkout with ordinary permissions rather than changing private/shared source permissions blindly.
- Composer/install/platform failure: inspect the first failing package/extension or filesystem permission. Preserve
  `composer.lock`; do not use update, ignore-platform flags or sibling dependencies as recovery. Missing/mismatched
  installed dependencies require explicit preparation before retrying the gate.
- Coverage capability failure: rebuild with `./bin/up`, ensure `XDEBUG_MODE=coverage`, then rerun `./bin/doctor`.
  Diagnose Unit failures with the focused command above. Missing or malformed Unit evidence cannot pass.
- A named `[build] FAIL` stops immediately with the command's nonzero status. Read the preceding tool output;
  later phases have not run. A retained report from an earlier/failed run is not a new successful verdict.

## Canonical phases

The thin `bin/build` resolves the checkout, runs read-only Python planning validation on the host, and invokes
`scripts/build.php` through guarded `./bin/exec` in the already-running `api` service. PHP runs these named,
fail-fast phases with inherited tool output:

1. Prepared runtime and locked dependencies (the same capability inspection used by doctor).
2. Strict, noninteractive Composer validation, then actual platform requirements.
3. PHP syntax for `config/`, `public/`, `scripts/` and `src/`.
4. Installed Fight Common PHPCS, PHPStan without progress, Deptrac with fail-on-uncovered/report-uncovered/
   report-skipped, then Deptrac unassigned without cache.
5. Rector dry-run for `src/` and `tests/`, without progress.
6. Unit once, generating a fresh distinct Clover artifact; exact Unit coverage validation.
7. Integration once, then Functional once, both without coverage so they cannot overwrite Unit evidence.

All three suites retain `--fail-on-skipped --fail-on-notice --fail-on-warning`. No focused wrapper is called to
add a second suite pass. Caches live in `var/cache/<tool>`; reports live in `var/reports/`. The gate performs no
image build/pull, dependency installation/update or implicit preparation. T-00010 replaces only T-00007's
per-build ephemeral-image/install mechanics and shared Clover overwrite behavior, not its checks or stronger
coverage contract. The historical completion records remain unchanged.

## Coverage evidence

The canonical Unit run explicitly writes `var/reports/coverage/unit/clover.xml`, removing that path immediately
before the run so stale output cannot satisfy the check. `./bin/coverage` and the gate's coverage phase inspect
that exact path. PHPUnit's shared configuration no longer writes a report for arbitrary suite selections; a
focused Unit run must request the path explicitly as above. Do not write combined-suite coverage to the Unit path.

Xdebug supplies coverage; PHPUnit retains `requireCoverageMetadata`, `includeUncoveredFiles`, and production
`src/` excluding only `src/Adapter/Kernel.php`. `scripts/check-coverage.php` requires exact covered/total statement
equality and rejects production ignore annotations, missing/malformed reports and contradictory metrics. No
combined-suite coverage can satisfy this Unit-only contract. Meaningful assertions and all retained journeys
remain required; do not manufacture tests or exclusions to obtain 100%.

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

Hosted CI explicitly runs `./bin/prepare`, then this same gate, and retains the Unit XML. For a PR it checks out the
literal source-head SHA rather than GitHub's synthetic merge commit; push runs check out the event SHA. It logs
that tested OID. Source-head proof does not assert merge-candidate compatibility. Local and hosted results are
distinct: preserve accepted clean-clone/exact-head requirements, record actual run URL/event/tested OID, and never
substitute local success or workflow presence for required hosted proof.
