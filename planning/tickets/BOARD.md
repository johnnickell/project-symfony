# Work Board

## Routing index

Read only current human decisions and active work below; otherwise the first ready TASK.
Use [lightweight routing](../CONVENTIONS.md#lightweight-work-routing) for validation and evidence limits.
[Wayfinder status and frontiers](../wayfinder/README.md) answer the decision-routing question.
Use the [Roadmap](../ROADMAP.md) and current handoffs for planning phases; do not infer full decomposition from closed maps.
Read the referenced current section, not the full historical record. Missing handoff/phase needs owner input.

This stable path is the sole human-decision and TASK execution Board. Source records own metadata; marked sections
are generated. Requirements never become executable merely because they are approved.

<!-- planning:mode -->
Active lifecycle: approved, authorized TASKs form the execution frontier.
<!-- /planning:mode -->

## "What's Next?" Contract

Return the human decision under **Now** and the current **Active Work** TASK; if none is active, return the first
eligible TASK under **Ready Frontier**. If no work is eligible, say so. Do not start a second TASK because its ID
sorts earlier. Follow [CONVENTIONS.md](../CONVENTIONS.md) for authority, blockers, and completion.

## Now

Planning adoption is complete: [TASK-00001](../tasks/00001-TASK.md#cutover-and-closeout) and
[T-00008](00008-TICKET.md#explicit-closeout-review) are done, PR #14's implementation is merged, and the lifecycle
is active. No adoption approval remains pending. The cutover follow-up has
[independent acceptance](../tasks/00001-TASK.md#independent-cutover-acceptance) and is published as
[PR #15](https://github.com/johnnickell/project-symfony/pull/15). Human approval/merge of that follow-up is a separate
delivery action, not another adoption gate.

[TASK-00002 — Codify Symfony Engineering and Independent Review Guidance](../tasks/00002-TASK.md) and its parent
[T-00009](00009-TICKET.md) are complete. Human review/merge of
[PR #19](https://github.com/johnnickell/project-symfony/pull/19) is next; delivery status is on the PR.
The engineering-alignment planning handoff remains `/skill:to-tasks T-00010` for
[reusable local and hosted verification](00010-TICKET.md), with John accepting the proposed TASK scope before
execution. No TASK is authorized merely by requirement approval. Detailed evidence remains ignored.

John approved the standalone PHP security-repair scope and creation of
[TASK-00003 — Require Patched PHP Runtimes](../tasks/00003-TASK.md). The selected isolated worktree is
`.runs/worktrees/task-00003-patched-php`, on `feature/task-00003-patched-php` from `develop`.
John subsequently invoked Build TASK-00003 there; independent Review accepted the implementation and applicable
QA passed. It is published for human review and merge as [PR #20](https://github.com/johnnickell/project-symfony/pull/20).
This urgent runtime repair remains separate from T-00009 and WF-002's broader topology.

John approved [EPIC-00002 — Remediate Symfony HttpFoundation CVE-2026-108082](../epics/00002-EPIC.md).
John accepted [TICKET-00013 — Require a Patched Compatible HttpFoundation Baseline](00013-TICKET.md), completing
EPIC-00002 requirement decomposition. John also approved
[TASK-00004 — Upgrade HttpFoundation and Enforce the Patched Baseline](../tasks/00004-TASK.md), completing TASK
decomposition for this EPIC. John selected the main checkout and invoked Build TASK-00004, authorizing implementation
and owned commits on `feature/task-00004-patched-http-foundation`. The implementation handoff awaits independent
`/skill:review TASK-00004` and applicable QA, coordinated by Build. This covers the latest compatible stable
HttpFoundation upgrade with a floor of 8.1.9, not unrelated upgrades or ongoing security-gate automation.

T-00004 retains its separate Fight Common 2.0 evidence gate. It is not a TASK or permission for package work.

## Wayfinder Review

No unblocked grillable decision is established. The active
[Symfony AccessControl Starter Application](../wayfinder/symfony-access-control-application-map.md) has an available
research frontier: [WF-001 — Released Package Contract Audit](../wayfinder/tickets/WF-001-released-package-contract-audit.md).
The requested released v0.8.0 / Common v1.4.0 baseline is installed; the full contract/schema/regression audit remains
open. Do not infer fixed findings or application authority from installation, or create duplicate upstream bug work.
WF-002 is closed. WF-003 and dependent decisions remain blocked until WF-001 is accepted.

## Active Work

<!-- planning:active -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:active -->

## Ready Frontier

<!-- planning:ready -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:ready -->

## Waiting

<!-- planning:waiting -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:waiting -->

## Human Action

<!-- planning:human -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 4 | [TASK-00004 — Upgrade HttpFoundation and Enforce the Patched Baseline](../tasks/00004-TASK.md) | [TICKET-00013 — Require a Patched Compatible HttpFoundation Baseline](00013-TICKET.md) | ready-for-human | — | — |
<!-- /planning:human -->

## Needs Info

<!-- planning:info -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:info -->

## Needs Triage

<!-- planning:triage -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:triage -->

## Recently Closed TASKs

<!-- planning:closed -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](../tasks/00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](00008-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/15 |
| 2 | [TASK-00002 — Codify Symfony Engineering and Independent Review Guidance](../tasks/00002-TASK.md) | [T-00009 — Establish Engineering and Independent Review Standards](00009-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/19 |
| — | [TASK-00003 — Require Patched PHP Runtimes](../tasks/00003-TASK.md) | — | done | — | https://github.com/johnnickell/project-symfony/pull/20 |
<!-- /planning:closed -->

## Legacy Work Awaiting Authority

This is visibility, not a second execution frontier. Legacy identities and package gates remain unchanged.

<!-- planning:legacy -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | [T-00004 — Prepare Fight Common 2.0 Migration](00004-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | needs-info | — | — |
<!-- /planning:legacy -->

## Legacy Completion History

| Ticket | Parent PRD | Outcome |
|--------|------------|---------|
| [T-00007 — Establish the Lean Symfony Pre-Submit Quality Gate](00007-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Replaced candidate certification with one Docker-backed build: released Fight Common 1.2, its installed PHPCS standard, exact Unit coverage, and retained Integration and Functional journeys. |
| [T-00005 — Establish the Canonical Symfony Pre-Submit Quality Gate](00005-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Made `./bin/build` the single clean-clone local and hosted gate with Composer candidate validation, latest/lowest boot journeys, receipt authority, PHPCS, PHPStan, Deptrac, Rector, exact statement coverage, production installation, and the hardened two-service runtime. |
| [T-00006 — Re-certify Rewritten Fight Common Candidate](00006-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Re-certified the tree-equivalent rewritten Fight Common candidate with fresh latest/lowest locks, receipt digests, and the canonical build. |
| [T-00003 — Establish the Symfony Complete Platform Profile](00003-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Repaired PR #6 around adapter-owned entrypoints, explicit web middleware, private capability-scoped providers, interface-driven compiler-pass autoconfiguration with narrow lazy-service visibility and direct boundary proof, production-binding test exposure, durable locks, and exact receipt authority with no event-sourcing infrastructure. |
| [T-00002 — Establish the Full-Stack Symfony Web Foundation](00002-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Added the Runtime-backed front controller, PHP configuration, Twig-rendered home page, and Compose-managed Nginx-to-PHP-FPM runtime. |
| [T-00001 — Establish the Canonical Symfony Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Established canonical planning authority, Docker-backed Composer and PHPUnit commands, a `var/cache/<tool>` convention, scoped ignore rules, and a clean production-install check. |

Canonical completion evidence remains in the unchanged legacy records. Requirements needing decomposition or
closeout are visible in the [Roadmap planning frontier](../ROADMAP.md#planning-frontier), not these TASK rows.
