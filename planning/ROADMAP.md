# Roadmap

## In progress

<!-- planning:epics -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](epics/00001-EPIC.md) | — | in-progress | — | — |
<!-- /planning:epics -->

[PRD-00002](specs/00002-PRD.md) remains the continuing legacy authority: the lean Fight Common 1.2 gate is adopted,
while the separate 2.0 migration remains needs-info. Its status is available in the [legacy index](specs/README.md).

## Route to 1.0

1. Decompose approved [T-00009](tickets/00009-TICKET.md) into bounded TASKs, then record their execution authority.
   Planning adoption is complete through [TASK-00001's cutover](tasks/00001-TASK.md#cutover-and-closeout);
   no additional bootstrap approval is needed. There is currently no executable TASK.
2. Follow the retained requirement dependencies for gate/coverage and existing presentation/testing alignment.
   Preserve exact Unit coverage and separate local/hosted evidence. This is unversioned foundation work, not
   release authority or the complete AccessControl starter handoff.
3. Revisit 2.0 only after Fight Common publishes its migration authority. T-00004 continues under legacy rules.

## Planning Frontier

These are decomposition/closeout decisions, not executable TASKs. Parent requirements and real blockers remain
visible while the [Work Board](tickets/BOARD.md) owns execution and the immediate human decision.

<!-- planning:frontier -->
- [T-00009 — Establish Engineering and Independent Review Standards](tickets/00009-TICKET.md): Decompose into TASKs after prerequisites.
- [T-00010 — Establish Reusable Local and Hosted Verification](tickets/00010-TICKET.md): Decompose into TASKs after prerequisites; unfinished T-00009.
- [T-00011 — Align Owned PHP and Homepage Presentation](tickets/00011-TICKET.md): Decompose into TASKs after prerequisites; unfinished T-00009; unfinished T-00010.
- [T-00012 — Align Behavioral Tests and Framework Diagnostics](tickets/00012-TICKET.md): Decompose into TASKs after prerequisites; unfinished T-00010; unfinished T-00011.
<!-- /planning:frontier -->

## Completed / Released

The governed Symfony Starter Foundation is complete: T-00001 and T-00002 have successful repository-local and hosted `./bin/build` receipts.

Three-level planning adoption is complete under [T-00008](tickets/00008-TICKET.md#explicit-closeout-review):
TASK-00001 has independent acceptance, PR #14's implementation is merged, and the authorized lifecycle cutover is
recorded. EPIC-00001 remains in progress for its four remaining requirement areas; this is not a release.
