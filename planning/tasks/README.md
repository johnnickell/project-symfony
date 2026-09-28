# TASKs

TASKs own bounded implementation, verification, and independent acceptance. The
[existing Board](../tickets/BOARD.md) is the sole execution frontier; this index is not a second ranking authority.
Read [CONVENTIONS.md](../CONVENTIONS.md) and the actual TASK before execution.

<!-- planning:records -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](../tickets/00008-TICKET.md) | ready-for-human | — | — |
<!-- /planning:records -->

TASK-00001 is the only pre-cutover bootstrap exception. General routing is gated by the
[lifecycle control](../README.md#cutover-control); implemented tooling does not grant independent acceptance.
Use [_TASK_TEMPLATE.md](_TASK_TEMPLATE.md) for subsequent approved work after verified cutover, and inspect
live/archive identities before allocating IDs. New records do not authorize themselves.
