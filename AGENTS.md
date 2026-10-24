# Fight Symfony Starter

## Work routing

For “What's next?”, `next`, or an unqualified `/ask-matt`, go straight to the
[Board](planning/tickets/BOARD.md), before general orientation. Follow
[lightweight routing](planning/CONVENTIONS.md#lightweight-work-routing): run the read-only planning validator once,
read the current human decision and all active TASKs, otherwise the first executable ready TASK in Board order.
Use the [Wayfinder inventory](planning/wayfinder/README.md) and current planning handoffs; do not infer completed
decomposition from closed maps or child counts. Recommend and stop. Full execution guidance loads only when invoked.
The Board retains its stable path; shared skills use the [routing substitution](docs/engineering/PROJECT_CONTEXT.md#routing-only-context).

## Project guidance

For execution or substantive planning, read [README](README.md), [Architecture](ARCHITECTURE.md),
[Foundation](planning/FOUNDATION.md), [planning authority](planning/README.md), [conventions](planning/CONVENTIONS.md),
and the selected record/parents. Apply [shared skill project context](docs/engineering/PROJECT_CONTEXT.md) to provider
references; the invocation checkout remains the target. Keep installed skills with their provider, without copies,
symlinks, global setup or runtime enrollment. Use the installed writing-for-agents skill for instruction changes.

- [Engineering standards](docs/engineering/STANDARDS.md): ownership, interfaces, PHP, HTTP and meaningful tests.
- [Execution](docs/engineering/EXECUTION.md): implementation, repair, verification and committed handoff.
- [Review](docs/engineering/REVIEW.md), [QA](docs/engineering/QA.md), [Landing](docs/engineering/LANDING.md): independent
  acceptance, behavioral evidence and authorized publication, with human merge separate.
- [Handoffs](docs/engineering/HANDOFFS.md), [Scratch](docs/engineering/SCRATCH.md), [Build](docs/engineering/BUILD.md):
  next actions, canonical ignored evidence and actual verification mechanics.

## Non-negotiable boundaries

Consume Fight Common and Fight AccessControl only as Composer packages. Never copy Domain/Application source,
reach into package internals or add a Fight bundle. Symfony owns namespace loading, autoconfiguration, aliases,
compiler passes, environment/HTTP/security composition and persistence/presentation adapters. Keep `src/*` and
inward dependencies; introduce new boundaries only for demonstrated use cases.

Use `handle()` for Actions and `respond()` for Responders. Preserve HTML and the explicit web JSON middleware /
bare-Kernel console split. Do not implement login, persistence, browser journeys, releases, publishing or visibility
transitions without local requirement and explicit TASK authority; legacy work retains its documented exception.

Preserve exact Unit-only owned-production statement coverage and meaningful integration/HTTP journeys. Never
weaken required hosted proof or public API compatibility to match another application. Do not create or restore
framework-support certification, receipt machinery or dependency certification matrices. Validate tooling directly
with owning commands, outside product suites; do not seed invalid fixtures to test the tools.

## Checkout, scratch and evidence

Choose the current checkout or an isolated worktree explicitly before implementation. Create feature branches
from `develop`, preferably `feature/task-NNNNN-<slug>`; preserve an existing authorized branch. Never commit directly
to `develop` or `main`, stage unrelated work, force-push, or infer publication authority from a commit.

Use categorized `.runs/` top-level directories from [Scratch](docs/engineering/SCRATCH.md), not loose dated folders.
Keep worktrees under `.runs/worktrees/`, runtime under `var/`, and tool caches under `var/cache/<tool>`. Preserve
active/shared resources; archive legacy scratch only through an inspected reversible manifest under explicit authority.
Never stage scratch. Canonical Review/QA reports and detailed execution evidence remain ignored; do not append new
tracked acceptance narratives or create planning records merely to store receipts. Existing planning requirements
and validators remain authoritative until a separately approved and verified storage cutover.

## Pre-submit gate

Use repository-owned `./bin/build`, `./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, `./bin/console`,
`./bin/planning-check`, and `./bin/exec`. `./bin/build` is the single noninteractive local/hosted gate; it is not the
shared `/skill:build` coordinator, whose explicit invocation has broader work/review/QA/publication authority.

For a long noninteractive build, use fresh task/run-specific names and paths:

```sh
screen -dmS <task>-build /bin/zsh -lc './bin/build > /private/tmp/<task>-build.log 2>&1; print -r -- $? > /private/tmp/<task>-build.exit'
```

Inspect the owned log and require an exit file containing `0`. Never reuse an old exit as proof or treat a
foreground timeout as a build result. Run the canonical gate before committing or creating a PR.

Before final commit/publication:

1. Record actual implementation/checks in ignored task evidence, with counts, coverage, warnings and limitations.
2. Keep `ready-for-human` while independent Spec/Standards acceptance is pending; a contributor cannot self-accept.
3. Apply automatic parent completion with any authorized terminal TASK update; no separate parent review gate.
4. Synchronize affected authored Board/Wayfinder/Roadmap navigation without adding acceptance narratives.
5. Preserve dependency history. Run `./bin/planning-check --write`, then `./bin/planning-check` for authorized metadata edits.
6. Run `git diff --check` and `./bin/build`; inspect and stage only owned changes.
7. Keep acceptance, QA, hosted proof, publication, human merge, release and deployment distinct.

Never archive tracked planning as a completion side effect. Run `./bin/archive-planning` only on explicit request,
review its dry run, then apply the exact selection. Scratch relocation is not planning archive authority.
