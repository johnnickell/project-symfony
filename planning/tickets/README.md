# TICKETs and Legacy Tickets

New TICKETs own approved requirements, not one-PR implementation. They link directly to an EPIC; TASKs own execution
and independent acceptance. See [CONVENTIONS.md](../CONVENTIONS.md) and the sole [Work Board](BOARD.md).

T-00001 through T-00007 remain legacy PRD-parented executable records. T-00008 through T-00012 retain their original
IDs as transitional requirements under EPIC-00001. New requirements use TICKET-NNNNN starting at the next unused
number across live and archive ticket files. No legacy identity is reused, renamed, or reinterpreted.

<!-- planning:records -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | [T-00001 — Establish the Canonical Symfony Starter Foundation](00001-TICKET.md) | [PRD-00001 — Symfony Starter Product and Walking-Slice Acceptance](../specs/00001-PRD.md) | done | — | — |
| — | [T-00002 — Establish the Full-Stack Symfony Web Foundation](00002-TICKET.md) | [PRD-00001 — Symfony Starter Product and Walking-Slice Acceptance](../specs/00001-PRD.md) | done | — | — |
| — | [T-00003 — Establish the Symfony Complete Platform Profile](00003-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | done | — | — |
| — | [T-00004 — Prepare Fight Common 2.0 Migration](00004-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | needs-info | — | — |
| — | [T-00005 — Establish the Canonical Symfony Pre-Submit Quality Gate](00005-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | done | — | — |
| — | [T-00006 — Re-certify Rewritten Fight Common Candidate](00006-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | done | — | — |
| — | [T-00007 — Establish the Lean Symfony Pre-Submit Quality Gate](00007-TICKET.md) | [PRD-00002 — Fight Common 1.2 Adoption and Lean Quality Gate](../specs/00002-PRD.md) | done | — | — |
| — | [T-00008 — Adopt the Three-Level Planning Lifecycle](00008-TICKET.md) | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](../epics/00001-EPIC.md) | in-progress | — | — |
| — | [T-00009 — Establish Engineering and Independent Review Standards](00009-TICKET.md) | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](../epics/00001-EPIC.md) | needs-info | [T-00008 — Adopt the Three-Level Planning Lifecycle](00008-TICKET.md); unfinished T-00008 | — |
| — | [T-00010 — Establish Reusable Local and Hosted Verification](00010-TICKET.md) | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](../epics/00001-EPIC.md) | needs-info | [T-00009 — Establish Engineering and Independent Review Standards](00009-TICKET.md); unfinished T-00009 | — |
| — | [T-00011 — Align Owned PHP and Homepage Presentation](00011-TICKET.md) | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](../epics/00001-EPIC.md) | needs-info | [T-00009 — Establish Engineering and Independent Review Standards](00009-TICKET.md), [T-00010 — Establish Reusable Local and Hosted Verification](00010-TICKET.md); unfinished T-00009; unfinished T-00010 | — |
| — | [T-00012 — Align Behavioral Tests and Framework Diagnostics](00012-TICKET.md) | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](../epics/00001-EPIC.md) | needs-info | [T-00010 — Establish Reusable Local and Hosted Verification](00010-TICKET.md), [T-00011 — Align Owned PHP and Homepage Presentation](00011-TICKET.md); unfinished T-00010; unfinished T-00011 | — |
<!-- /planning:records -->

[T-00008](00008-TICKET.md#approved-bootstrap-exception) owns TASK-00001's authorized bootstrap and the pending
verified cutover. Requirement approval is separate from execution authority. T-00004 continues under legacy rules
with its unmet package gate and PRD-00002 parent intact; no successor or archive operation is implied.
