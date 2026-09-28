---
id: T-00009
epic: EPIC-00001
title: Establish Engineering and Independent Review Standards
status: ready-for-human
blocked_by: T-00008
approved: yes
---

# Establish Engineering and Independent Review Standards

## Outcome

Give contributors and independent reviewers one consistent, self-contained local contract for engineering and
TASK acceptance, replacing conflicting guidance without importing another application's framework or machinery.

## Approval and execution boundary

John approved this requirement area under [EPIC-00001](../epics/00001-EPIC.md). This is a requirement TICKET,
not executable work. [T-00008](00008-TICKET.md) has completed the planning cutover. John approved one documentation
TASK, [TASK-00002](../tasks/00002-TASK.md), covering all eight acceptance criteria. `ready-for-human` records the
next decision: grant execution authority and choose the implementation checkout/worktree. Scope acceptance is not
implementation authorization or independent acceptance. The completed T-00008 dependency remains recorded; no new
PRD is needed.

## Scope

### Included use cases

- A contributor places a change with its actual owner: public Fight contracts remain package-owned, business policy
  stays with Domain, orchestration belongs in Application only when justified, and Symfony concerns stay in Adapter.
- A contributor can decide whether an owned orchestrator is warranted from missing package behavior, genuine policy
  or cross-boundary coordination, observable inputs/results/failures, and injected inward capabilities.
- A contributor follows consistent strict-types, final/readonly, constructor-injection, naming, namespace-cohesion,
  and docblock conventions, using the installed Fight Common standard without changing vendor code.
- An HTTP implementer understands Action/Responder responsibilities, the pure-presentation exception, safe Views,
  explicit wire mapping, and authorization on every entry path without preempting deferred API decisions.
- A test author selects meaningful owned behavior and integration contracts, preserves exact Unit-only coverage,
  uses regression-first repair where feasible, and verifies tooling directly rather than testing the tools.
- An independent reviewer performs Spec and Standards passes against TASK requirements and the exact snapshot,
  records findings and limitations, and cannot confuse the implementer's checks with independent acceptance.
- A maintainer distinguishes implementation completion from hosted verification, publication, merge, release, and
  deployment while preserving human merge control and explicit archive authority.

### Affected boundaries and documentation

Focused engineering/review guidance, agent instructions, contributor onboarding, and TASK handoff references must
agree with the migrated lifecycle. Link shared authority rather than creating duplicate rule sets. Identify precise
supersession of old instructions while retaining historical ticket outcomes. Actual production refactoring belongs
to [T-00011](00011-TICKET.md); gate changes belong to [T-00010](00010-TICKET.md).

### Exclusions

Production refactoring, dependency/package changes, gate implementation, new HTTP or security behavior, private
source identities in public guidance, new lifecycle redesign beyond T-00008, or automatic global standards rollout.

## Acceptance Criteria

- [ ] AC-01 — Guidance states inward dependency direction, direct package composition, no renaming/forwarding
      wrappers, and the explicit owned-orchestration test without authorizing speculative layers or service location.
- [ ] AC-02 — PHP/naming/documentation rules and justified framework exceptions are actionable, consistent with
      the epic, and distinguish consumer-owned code from immutable public-package contracts.
- [ ] AC-03 — HTTP rules separate transport translation, presentation, and authoritative policy; preserve the HTML
      homepage exception; and route exact API/security decisions to their existing Wayfinder owners.
- [ ] AC-04 — Test-selection and regression guidance preserve exact Unit-only coverage, meaningful adapter and
      integration behavior, and direct tooling diagnostics without weakening assertions or widening exclusions.
- [ ] AC-05 — Review guidance defines independent Spec and Standards passes, criterion-to-evidence mapping,
      snapshot identity, findings/disposition, warnings, unverified areas, and the next authorized action. One
      independent reviewer may perform both passes; a material implementation contributor cannot accept their work.
- [ ] AC-06 — TASK handoffs carry implementation and review evidence. Requirement progress, hosted status, PR
      publication, merge, release, and deployment remain distinct; missing required evidence prevents acceptance.
- [ ] AC-07 — Local instructions, contributor documentation, and handoff references agree. Exceptions identify
      their rationale, owner, affected requirement, acceptance, and verification path without private dependencies.
- [ ] AC-08 — Direct document/routing review and canonical verification establish the delivered guidance. No
      production behavior change or PHPUnit tests of documentation/tooling are introduced.

## Verification

During later authorized implementation:

- Trace one ordinary feature contribution and one revision or missing-evidence handoff through the actual guidance;
  confirm both resolve ownership, required proof, independent review, and where execution must stop.
- Compare the guidance with the epic's accepted rules and the verified T-00008 lifecycle. Inspect all callers for
  contradictory old completion or review instructions; preserve historical records rather than rewriting history.
- Run `./bin/planning-check`, `git diff --check`, and `./bin/build`; report fresh results and limitations. Obtain
  independent Spec and Standards review of the documentation implementation, not fabricated product tests.

## Applicability

This ticket introduces no business commands, queries, events, permissions, or request validators. It specifies how
future changes treat those boundaries. Security applicability is secret-safe evidence, independent authority, and
preservation of package/server policy ownership; it does not authorize new authentication or permissions. Existing
Composer contracts and Symfony runtime behavior are unchanged. No production adapter is modified by this scope.

## Implementation TASKs

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 2 | [TASK-00002 — Codify Symfony Engineering and Independent Review Guidance](../tasks/00002-TASK.md) | [T-00009 — Establish Engineering and Independent Review Standards](00009-TICKET.md) | ready-for-human | —; execution authorization missing | — |
<!-- /planning:children -->

## Completion Notes

Requirements and the single-TASK decomposition are approved; T-00008's verified cutover prerequisite is satisfied.
TASK-00002 owns the complete documentation outcome and awaits execution authority and checkout/worktree selection.
No guidance implementation or independent implementation acceptance is claimed. Requirement closeout remains pending
accepted delivery of its criteria and an explicit closeout review.
