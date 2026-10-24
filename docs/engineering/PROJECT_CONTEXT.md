# Shared skill project context

The invocation checkout is the target; the installed skill resource is its provider. Matching TASK IDs or a skill's
filesystem location never select a different repository. Resolve provider links from the supplied skill location,
not a guessed machine path. Do not copy the skill suite, create compatibility symlinks or install global resources.

## Routing-only context

For `next`, “What's next?” or an unqualified `/ask-matt`, read the routing entry in root AGENTS.md, then use
[lightweight routing](../../planning/CONVENTIONS.md#lightweight-work-routing). The sole Board remains
[planning/tickets/BOARD.md](../../planning/tickets/BOARD.md), not the provider's `planning/tasks/BOARD.md`.
Use local section names, status vocabulary, Wayfinder inventory and current handoffs. This stable Board path is
an explicit routing substitution, not a second Board. Do not load full execution context merely to recommend.

## Execution and substantive planning context

Read [README](../../README.md), [Architecture](../../ARCHITECTURE.md), [Foundation](../../planning/FOUNDATION.md),
[planning authority](../../planning/README.md), [conventions](../../planning/CONVENTIONS.md), the selected record
and accepted parents. Resolve Git root/common-directory/worktrees, branch/base and ownership before mutation.
Preserve an already selected checkout and authorized branch.

| Provider reference | Target authority |
| --- | --- |
| Architecture and application ownership ADR | `ARCHITECTURE.md`, accepted local requirements and installed public package contracts |
| Foundation | `planning/FOUNDATION.md` |
| Engineering, execution, review, QA, landing, handoffs, scratch, build | Corresponding documents in `docs/engineering/` |
| Detailed quality checklist | Provider checklist, applied only against local standards and accepted scope |
| Board | `planning/tickets/BOARD.md` |
| Planning templates, metadata, IDs, archive operations | Local `planning/CONVENTIONS.md` and directory templates |
| Runtime, preparation, gate, focused checks | Local `./bin/*` commands and `docs/engineering/BUILD.md`; Compose PHP service is `api`, not `web` |
| PR body template | `.github/pull_request_template.md` |
| Product-specific APIs, frontend, PostgreSQL, managed Runner or release machinery | Non-applicable unless a local requirement explicitly introduces that boundary |

Apply substitutions to nested references too. Preserve legacy `T-` requirement parents and PRDs; never rename
records to satisfy a provider example. New records still use the local allocation/approval rules. Provider
planning, historical receipts and managed infrastructure are not prerequisites or facts about this target.
An absent optional profile is not a blocker; a missing required capability or conflicting requirement is.

For delegated Build, put the resolved target/provider paths, checkout/base, branch, subject, instruction paths,
substitutions, runtime/gates, completion/authority rules, model pins and report locations in the ignored run ledger.
Pass its absolute path to every fresh role. Each child verifies Git identity and reads local instructions before
applying provider workflow references. A change of subject, requirements or provider needs reconciliation, not
silent reuse of a different delivery's reports or repair allowance.

## Pi and workflow availability

Use the already installed provider skills and harness. No repository-local `.pi/` tree or Jev enrollment is needed
for ordinary native tools. Confirm capabilities from supplied metadata/settings; `next` and `build` are explicitly
invoked skills and may intentionally be absent from automatic model selection. Skill installation does not grant
execution, publication or release authority.

Use native Pi for `/skill:next`, `/skill:build` and role selection. The legacy RPC `fight-terminal` client refuses
`/skill:*` selectors; follow its native-session selection/resume procedure rather than bypassing that refusal.
The optional deterministic script policy does not support ordinary unrestricted Build dispatch. Never disable an
executor/policy to make a workflow run. Jev discovery is optional and is not shell or filesystem isolation.

`./bin/build` runs the local gate only. Explicit `/skill:build TASK-NNNNN` delegates Engineer → independent
Review → applicable QA → Land under its documented authority, including non-force publication, but never merge,
release or deployment. A recommendation from `next` grants none of these effects. Resolve local execution and
publication requirements before dispatch. The default shared five-cycle repair budget covers Review and QA
repairs together; resumes preserve consumed cycles, model pins, history and uncertain effects.

Qualify the installed CLI, required hooks, models and tools before dispatch. Record versions as evidence, not Pi
version equality gates. Full process restart may be needed after harness source changes; do not reinstall,
reenroll or reset accounting as a compatibility shortcut. Native files/shell remain subject to actual user/OS
permissions; workflow roles are responsibilities, not a sandbox.
