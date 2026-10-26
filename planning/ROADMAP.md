# Roadmap

## Current delivery handoffs

<!-- planning:delivery -->
| Subject | Recorded delivery | Exact PR |
|---|---|---|
| None | — | — |
<!-- /planning:delivery -->

Current delivery actions use the [Board reconciliation](tickets/BOARD.md#now) and explicit delivery view above.
PR #15/#19/#20/#21/#22/#23/#25 publication checkpoints are preserved history; those subjects are verified
merged/integrated and request no merge or historical requery. Planning and unrelated qualification holds remain.

## In progress

<!-- planning:epics -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | [EPIC-00001 — Align Symfony Engineering Standards and Delivery](epics/00001-EPIC.md) | — | in-progress | — | — |
| — | [EPIC-00002 — Remediate Symfony HttpFoundation CVE-2026-108082](epics/00002-EPIC.md) | — | done | — | — |
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
main checkout, and invoked Build TASK-00004. The completed delivery is published for human review/merge as
[PR #21](https://github.com/johnnickell/project-symfony/pull/21). This is separate from TASK-00003's PHP repair
and EPIC-00001's unfinished alignment planning, with no deployment authority implied.

## Route to 1.0

1. Human review/merge of [TASK-00002](tasks/00002-TASK.md)'s
   [PR #19](https://github.com/johnnickell/project-symfony/pull/19) is next; [T-00009](tickets/00009-TICKET.md) is complete.
   John accepted [T-00010](tickets/00010-TICKET.md)'s complete decomposition into
   [TASK-00005](tasks/00005-TASK.md) (preparation), then [TASK-00006](tasks/00006-TASK.md) (local/hosted gate and
   durable Unit coverage). TASK-00005 is independently accepted with applicable QA passed and merged as
   [PR #22](https://github.com/johnnickell/project-symfony/pull/22). TASK-00006 is independently accepted with
   applicable QA passed and published for human review/merge as
   [PR #23](https://github.com/johnnickell/project-symfony/pull/23). John accepted
   [TASK-00007](tasks/00007-TASK.md) as [T-00011](tickets/00011-TICKET.md)'s complete decomposition.
   T-00011 is complete: TASK-00007 is independently accepted with applicable QA passed and is published for
   human review/merge as [PR #25](https://github.com/johnnickell/project-symfony/pull/25).
   John also accepted [TASK-00008](tasks/00008-TASK.md)
   as [T-00012](tickets/00012-TICKET.md)'s complete decomposition, without execution authority.
   EPIC-00001 implementation remains unfinished.
2. Follow the retained requirement dependencies for gate/coverage and existing presentation/testing alignment.
   Preserve exact Unit coverage and separate local/hosted evidence. This is unversioned foundation work, not
   release authority or the complete AccessControl starter handoff.
3. Revisit 2.0 only after Fight Common publishes its migration authority. T-00004 continues under legacy rules.

## Planning Frontier

These are decomposition/closeout decisions, not executable TASKs. Parent requirements and real blockers remain
visible while the [Work Board](tickets/BOARD.md) owns execution and the immediate human decision.

EPIC-00001's requirement/TASK decomposition is complete. T-00011 is complete through TASK-00007, which is
independently accepted with applicable QA passed and published for human review/merge as
[PR #25](https://github.com/johnnickell/project-symfony/pull/25). T-00012 has accepted TASK-00008, which still
needs execution authority and checkout selection. Use `/skill:next` for Board-based selection. No additional
decomposition or parent-closeout decision is pending for this epic; its implementation remains unfinished and the
separate AccessControl Wayfinder handoff remains open.

<!-- planning:frontier -->
None.
<!-- /planning:frontier -->

## Completed / Released

The governed Symfony Starter Foundation is complete: T-00001 and T-00002 have successful repository-local and hosted `./bin/build` receipts.

Three-level planning adoption is complete under [T-00008](tickets/00008-TICKET.md#explicit-closeout-review):
TASK-00001 has independent acceptance, PR #14's implementation is merged, and the authorized lifecycle cutover is
recorded. [T-00009](tickets/00009-TICKET.md) is also complete through [TASK-00002](tasks/00002-TASK.md).
EPIC-00001 remains in progress for T-00011 and T-00012; this is not a release.
