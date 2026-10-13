# AGENTS.md

## Work routing

For “What's next?”, `next`, or an unqualified `/ask-matt`, go straight to the [Board](planning/tickets/BOARD.md)
before general project orientation. Read its current human decision and active work; otherwise select the first
executable ready TASK in Board order. Use the [Wayfinder index](planning/wayfinder/README.md) for map status and current frontier pointers.
Follow [lightweight work routing](planning/CONVENTIONS.md#lightweight-work-routing) for validation,
bounded evidence reads and planning handoffs. Recommend and stop; load execution guidance when work is invoked.
This routing path does not require reading README, architecture, full project context, task history or receiving skills.

The Board retains its historical path. During `lifecycle: bootstrap` in `planning/README.md`, only the
explicitly authorized TASK-00001 exception may execute; general cutover requires independent acceptance.

## Project guidance

For execution or substantive planning changes, load the applicable guidance below. The read-only routing
path above takes precedence over broad orientation reads; it does not waive engineering or delivery rules.

Read `CONTEXT.md`, `planning/README.md`, `planning/CONVENTIONS.md`, and `planning/agents/` before changing
behavior. Work in independently verifiable vertical slices. Use the repository-owned `./bin/build`,
`./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, `./bin/console`, `./bin/planning-check`, and
`./bin/exec` commands; `./bin/build` is the single noninteractive local and hosted gate.

## Architecture boundary

`johnnickell/fight-common` and `johnnickell/fight-access-control` are consumed only as Composer packages.
Never copy their Domain or Application source, reach into package internals; there is no Fight bundle. Symfony owns
its namespace loading, autoconfiguration, compiler passes, aliases, environment configuration, HTTP/security
composition, and every persistence or presentation adapter.

Do not implement login, persistence, browser journeys, releases, publishing, or visibility transitions unless a
local requirement and approved TASK explicitly authorize that work (preserved legacy work follows its documented exception).

## Run and Worktree Isolation

Coordinate-build scratch belongs in `.runs/<YYYY-MM-DD>-<slug>/`. It is gitignored and must never be staged.

## Branch Conventions

Create feature branches from `develop`; prefer `feature/task-NNNNN-<slug>` for new TASKs. Preserve an existing
user-authorized branch. Choose the current checkout or an isolated worktree explicitly before implementation.
Never commit directly to `develop` or `main`.

## Pre-Submit Gate

For a long non-interactive build, run `screen -dmS <task>-build /bin/zsh -lc './bin/build > /private/tmp/<task>-build.log 2>&1; print -r -- $? > /private/tmp/<task>-build.exit'`, then inspect the log and require an exit file containing `0`; never treat foreground timeout output as a build result.

Always run before committing or creating a PR:

```bash
./bin/build
```

## Planning

See `planning/CONVENTIONS.md` for EPIC -> TICKET -> TASK ownership, legacy PRD/ticket preservation, the stable
Board, Wayfinder handoffs, templates, metadata, independent acceptance, and explicit-only archive operations. Never
archive planning records as a completion side effect; run `./bin/archive-planning` only on an explicit request,
review its dry run, and then apply it.

### Pre-PR Sync Checklist

Before final commit and PR for any feature or bug fix:

1. Record actual TASK implementation and verification; use `ready-for-human` while independent review is pending
2. Mark a TASK `done` only after independent Spec and Standards acceptance of the exact snapshot and required verification
3. Apply automatic parent completion to current requirements and EPICs; preserve legacy PRD authority
4. Update authored Board **Now**, Wayfinder continuity, and Roadmap strategy when their decisions change
5. Retain dependency history; unfinished blockers are derived, not removed from metadata
6. Run `./bin/planning-check --write`, then `./bin/planning-check` to refresh and validate marked views
7. Run the canonical build and inspect the owned diff before committing; publication, merge, release, and cutover remain separately authorized

When completing a TASK, apply [Automatic parent completion](planning/CONVENTIONS.md#automatic-parent-completion)
in the same operation; do not leave a separate parent assessment or closeout action for the user.

## Certification retirement

Test owned application behavior and meaningful package integrations. Do not create or restore framework-support
certification files, receipt readers/generators, dependency certification matrices, or tests of those mechanisms.
Validate build, configuration and planning tools directly with their owning commands, outside product suites.
Historical certification notes remain history, not current gates.
