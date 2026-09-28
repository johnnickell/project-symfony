# AGENTS.md

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

## Work Routing

When asked "What's next?" or invoked without a task, read `planning/tickets/BOARD.md`, the sole Board at its
stable historical path. Return the human decision under **Now** and the **Active Work** TASK; otherwise return
the first eligible TASK under **Ready Frontier**, or say none is eligible. Never select a requirement as executable.
Use `planning/CONVENTIONS.md` for authority and ordering. During `lifecycle: bootstrap` in `planning/README.md`,
only the explicitly authorized TASK-00001 exception may execute; general cutover requires independent acceptance.

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
3. Update parent requirement and epic progress without premature closeout; preserve legacy PRD authority
4. Update authored Board **Now**, Wayfinder continuity, and Roadmap strategy when their decisions change
5. Retain dependency history; unfinished blockers are derived, not removed from metadata
6. Run `./bin/planning-check --write`, then `./bin/planning-check` to refresh and validate marked views
7. Run the canonical build and inspect the owned diff before committing; publication, merge, release, and cutover remain separately authorized
