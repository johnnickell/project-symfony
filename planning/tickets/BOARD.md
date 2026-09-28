# Work Board

This stable path is the sole human-decision and TASK execution Board. Source records own metadata; marked sections
are generated. Requirements never become executable merely because they are approved.

<!-- planning:mode -->
Bootstrap mode: only explicitly authorized TASK-00001 may execute under T-00008's exception. General cutover awaits independent acceptance.
<!-- /planning:mode -->

## "What's Next?" Contract

Return the human decision under **Now** and the current **Active Work** TASK; if none is active, return the first
eligible TASK under **Ready Frontier**. If no work is eligible, say so. Do not start a second TASK because its ID
sorts earlier. Follow [CONVENTIONS.md](../CONVENTIONS.md) for authority, blockers, and completion.

## Now

Decide the separately authorized lifecycle cutover and closeout under
[T-00008](00008-TICKET.md#approved-bootstrap-exception). [TASK-00001](../tasks/00001-TASK.md#independent-acceptance)
has independent Spec and Standards acceptance after R1 and is being published under John's landing request.
It remains `ready-for-human` with `review: accepted` because actual cutover is still pending. Human PR approval/merge
is separate. The lifecycle remains bootstrap; no other TASK is currently executable.

T-00004 retains its separate Fight Common 2.0 evidence gate. It is not a TASK or permission for package work.

## Wayfinder Review

No active map currently has an unblocked grillable frontier.
[Symfony AccessControl Starter Application](../wayfinder/symfony-access-control-application-map.md) remains active:
[Local Development Runtime Contract](../wayfinder/tickets/WF-002-local-development-runtime-contract.md) is closed,
while [Released Package Contract Audit](../wayfinder/tickets/WF-001-released-package-contract-audit.md) awaits
installable AccessControl v0.2.0 reusable schemas and a public-contract delta audit. Symfony retains endpoint and
complete-document ownership. After that audit closes, the next human decision is
[ADR HTTP and OpenAPI Contract](../wayfinder/tickets/WF-003-adr-http-openapi-contract.md).

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
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](../tasks/00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](00008-TICKET.md) | ready-for-human | — | — |
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
| — | None | — | — | — | — |
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
