# Ticket Board

Ticket files are canonical for status and blockers; this board is canonical for recommended execution order.

## "What's Next?" Contract

When an unqualified "What's next?" is asked:

1. **Human decision:** return the item under **Now** when it still requires judgment.
2. **Implementation:** return the first ticket under **Ready Frontier**.
3. If the question is unqualified, return both targets. Never choose by ticket number alone.

## Now

No local human decision is pending. The Fight Common 2.0 migration remains visible as `needs-info` until its
owning package publishes the contract, deprecation-removal inventory, and migration guide.

## Wayfinder Review

No active map currently has an unblocked grillable frontier.
[Symfony AccessControl Starter Application](../wayfinder/symfony-access-control-application-map.md) remains active:
[Local Development Runtime Contract](../wayfinder/tickets/WF-002-local-development-runtime-contract.md) is closed,
while [Released Package Contract Audit](../wayfinder/tickets/WF-001-released-package-contract-audit.md) awaits
installable AccessControl v0.2.0 reusable schemas and a public-contract delta audit. Symfony retains endpoint and
complete-document ownership. After that audit closes, the next human decision is
[ADR HTTP and OpenAPI Contract](../wayfinder/tickets/WF-003-adr-http-openapi-contract.md).

## Ready Frontier

| Suggested Order | Ticket | Parent PRD | Why now |
| --- | --- | --- | --- |
No ticket is currently ready for implementation.

## Waiting

No ticket is currently waiting on an unfinished local dependency.

## Needs Info

| Ticket | Parent PRD | Missing decision or evidence |
| --- | --- | --- |
| [T-00004 — Prepare Fight Common 2.0 Migration](00004-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Fight Common 2.0 contract, deprecation-removal inventory, and migration guide. |

## Recently Done

| Ticket | Parent PRD | Outcome |
|--------|------------|---------|
| [T-00007 — Establish the Lean Symfony Pre-Submit Quality Gate](00007-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Replaced candidate certification with one Docker-backed build: released Fight Common 1.2, its installed PHPCS standard, exact Unit coverage, and retained Integration and Functional journeys. |
| [T-00005 — Establish the Canonical Symfony Pre-Submit Quality Gate](00005-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Made `./bin/build` the single clean-clone local and hosted gate with Composer candidate validation, latest/lowest boot journeys, receipt authority, PHPCS, PHPStan, Deptrac, Rector, exact statement coverage, production installation, and the hardened two-service runtime. |
| [T-00006 — Re-certify Rewritten Fight Common Candidate](00006-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Re-certified the tree-equivalent rewritten Fight Common candidate with fresh latest/lowest locks, receipt digests, and the canonical build. |
| [T-00003 — Establish the Symfony Complete Platform Profile](00003-TICKET.md) | [PRD-00002](../specs/00002-PRD.md) | Repaired PR #6 around adapter-owned entrypoints, explicit web middleware, private capability-scoped providers, interface-driven compiler-pass autoconfiguration with narrow lazy-service visibility and direct boundary proof, production-binding test exposure, durable locks, and exact receipt authority with no event-sourcing infrastructure. |
| [T-00002 — Establish the Full-Stack Symfony Web Foundation](00002-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Added the Runtime-backed front controller, PHP configuration, Twig-rendered home page, and Compose-managed Nginx-to-PHP-FPM runtime. |
| [T-00001 — Establish the Canonical Symfony Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Established canonical planning authority, Docker-backed Composer and PHPUnit commands, a `var/cache/<tool>` convention, scoped ignore rules, and a clean production-install check. |
