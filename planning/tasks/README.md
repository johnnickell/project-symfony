# TASKs

TASKs own bounded implementation, verification, and independent acceptance. The
[existing Board](../tickets/BOARD.md) is the sole execution frontier; this index is not a second ranking authority.
Read [CONVENTIONS.md](../CONVENTIONS.md) and the actual TASK before execution.

<!-- planning:records -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](../tickets/00008-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/15 |
| 2 | [TASK-00002 — Codify Symfony Engineering and Independent Review Guidance](00002-TASK.md) | [T-00009 — Establish Engineering and Independent Review Standards](../tickets/00009-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/19 |
| 4 | [TASK-00004 — Upgrade HttpFoundation and Enforce the Patched Baseline](00004-TASK.md) | [TICKET-00013 — Require a Patched Compatible HttpFoundation Baseline](../tickets/00013-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/21 |
| 5 | [TASK-00005 — Prepare the Compose PHP Service for Reusable Verification](00005-TASK.md) | [T-00010 — Establish Reusable Local and Hosted Verification](../tickets/00010-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/22 |
| 6 | [TASK-00006 — Run One Reusable Local and Hosted Gate with Durable Unit Coverage](00006-TASK.md) | [T-00010 — Establish Reusable Local and Hosted Verification](../tickets/00010-TICKET.md) | done | [TASK-00005 — Prepare the Compose PHP Service for Reusable Verification](00005-TASK.md) | https://github.com/johnnickell/project-symfony/pull/23 |
| — | [TASK-00003 — Require Patched PHP Runtimes](00003-TASK.md) | — | done | — | https://github.com/johnnickell/project-symfony/pull/20 |
<!-- /planning:records -->

TASK-00001 completed the bootstrap and [verified cutover](00001-TASK.md#cutover-and-closeout). General routing now
uses the active [lifecycle control](../README.md#cutover-control). Use [_TASK_TEMPLATE.md](_TASK_TEMPLATE.md) for
subsequent approved work and inspect live/archive identities before allocating IDs. New records do not authorize
themselves; each implementation still needs explicit scope and execution authority.
