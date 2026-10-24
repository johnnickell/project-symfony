# Canonical verification

`./bin/build` is the sole complete local gate and the command used by hosted CI. Focused wrappers shorten
iteration; they do not replace it. A successful gate is not independent Review, QA, publication or merge.

## Current preparation and commands

Use repository-owned wrappers, not host PHP/Composer or a sibling vendor directory:

```sh
./bin/up
./bin/composer install --no-interaction --prefer-dist --no-progress
./bin/phpunit
./bin/build
```

`./bin/up` explicitly builds/starts the local Nginx and PHP-FPM services; `--logs` follows logs. The PHP Compose
service is `api`; `./bin/exec` executes there and `./bin/down` stops the selected stack. Inspect project identity,
mounts and port ownership before startup, especially in worktrees. Current HTTP port is 18083; do not replace
another checkout's services. No database/worker/browser runtime is provisioned by this starter.

The current `bin/build` first validates planning on the host, builds `etc/docker/build/Dockerfile`, then runs a
one-off container against this checkout. It validates Composer and installs the lock, lints PHP, runs the installed
Fight Common PHPCS standard, PHPStan, Deptrac (including uncovered/unassigned checks), Rector dry-run, Unit tests
with exact coverage validation, then Integration and Functional suites. Each suite runs once. Retain all phases,
warning/skip failure flags and the full Unit-only requirement.

This is the current operational contract, not the future T-00010 design. Explicit preparation with a thin
running-container PHP phase orchestrator remains separately scoped implementation. Do not claim this guidance
removed image rebuilds, dependency installation or other gate mechanics.

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
