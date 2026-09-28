# TASKs

TASKs own bounded implementation, verification, and independent acceptance. The
[existing Board](../tickets/BOARD.md) is the sole execution frontier; this index is not a second ranking authority.
Read [CONVENTIONS.md](../CONVENTIONS.md) and the actual TASK before execution.

<!-- planning:records -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](../tickets/00008-TICKET.md) | done | — | https://github.com/johnnickell/project-symfony/pull/14 |
<!-- /planning:records -->

TASK-00001 completed the bootstrap and [verified cutover](00001-TASK.md#cutover-and-closeout). General routing now
uses the active [lifecycle control](../README.md#cutover-control). Use [_TASK_TEMPLATE.md](_TASK_TEMPLATE.md) for
subsequent approved work and inspect live/archive identities before allocating IDs. New records do not authorize
themselves; each implementation still needs explicit scope and execution authority.
