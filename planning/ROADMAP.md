# Roadmap

## In progress

<!-- planning:epics -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](epics/00001-EPIC.md) | — | in-progress | — | — |
| — | [EPIC-00002 — Remediate Symfony HttpFoundation CVE-2026-108082](epics/00002-EPIC.md) | — | in-progress | — | — |
<!-- /planning:epics -->

[PRD-00002](specs/00002-PRD.md) remains the continuing legacy authority: the lean Fight Common 1.2 gate is adopted,
while the separate 2.0 migration remains needs-info. Its status is available in the [legacy index](specs/README.md).

## Standalone security repair

[TASK-00003 — Require Patched PHP Runtimes](tasks/00003-TASK.md) has independent Review acceptance and applicable
QA PASS, and is published for human review and merge as [PR #20](https://github.com/johnnickell/project-symfony/pull/20).
This PHP-only repair does not implement WF-002's broader topology or grant production authority.

## Symfony advisory remediation

[EPIC-00002 — Remediate Symfony HttpFoundation CVE-2026-108082](epics/00002-EPIC.md) is approved for a bounded
upgrade to the latest compatible stable HttpFoundation release, with a resolution floor of 8.1.9. John accepted
[TICKET-00013](tickets/00013-TICKET.md) and its single implementation [TASK-00004](tasks/00004-TASK.md), selected the
main checkout, and invoked Build TASK-00004. Decomposition is complete; the implementation handoff awaits independent
`/skill:review TASK-00004` and applicable QA, coordinated by Build. This is separate from TASK-00003's PHP repair
and EPIC-00001's unfinished alignment planning, with no deployment authority implied.

## Route to 1.0

1. Human review/merge of [TASK-00002](tasks/00002-TASK.md)'s
   [PR #19](https://github.com/johnnickell/project-symfony/pull/19) is next; [T-00009](tickets/00009-TICKET.md) is complete.
   Decompose [T-00010](tickets/00010-TICKET.md) into proposed TASKs via `/skill:to-tasks T-00010`, then obtain John's
   TASK-scope acceptance and execution authority. Planning cutover is already complete.
2. Follow the retained requirement dependencies for gate/coverage and existing presentation/testing alignment.
   Preserve exact Unit coverage and separate local/hosted evidence. This is unversioned foundation work, not
   release authority or the complete AccessControl starter handoff.
3. Revisit 2.0 only after Fight Common publishes its migration authority. T-00004 continues under legacy rules.

## Planning Frontier

These are decomposition/closeout decisions, not executable TASKs. Parent requirements and real blockers remain
visible while the [Work Board](tickets/BOARD.md) owns execution and the immediate human decision.

<!-- planning:frontier -->
- [T-00010 — Establish Reusable Local and Hosted Verification](tickets/00010-TICKET.md): Decompose into TASKs after prerequisites.
- [T-00011 — Align Owned PHP and Homepage Presentation](tickets/00011-TICKET.md): Decompose into TASKs after prerequisites; unfinished T-00010.
- [T-00012 — Align Behavioral Tests and Framework Diagnostics](tickets/00012-TICKET.md): Decompose into TASKs after prerequisites; unfinished T-00010; unfinished T-00011.
<!-- /planning:frontier -->

## Completed / Released

The governed Symfony Starter Foundation is complete: T-00001 and T-00002 have successful repository-local and hosted `./bin/build` receipts.

Three-level planning adoption is complete under [T-00008](tickets/00008-TICKET.md#explicit-closeout-review):
TASK-00001 has independent acceptance, PR #14's implementation is merged, and the authorized lifecycle cutover is
recorded. [T-00009](tickets/00009-TICKET.md) is also complete through [TASK-00002](tasks/00002-TASK.md).
EPIC-00001 remains in progress for T-00010 through T-00012; this is not a release.
