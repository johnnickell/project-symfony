---
id: T-00012
epic: EPIC-00001
title: Align Behavioral Tests and Framework Diagnostics
status: needs-info
blocked_by: T-00010,T-00011
approved: yes
---

# Align Behavioral Tests and Framework Diagnostics

## Outcome

Give every retained verification mechanism a clear owned contract and proportionate boundary, replacing genuinely
wiring-only tests with equivalent direct diagnostics where justified without losing meaningful integration
behavior or exact Unit coverage.

## Approval and execution boundary

John approved this requirement area under [EPIC-00001](../epics/00001-EPIC.md). This is a requirement TICKET,
not a TASK or permission for bulk test deletion. `needs-info` records the missing verified planning cutover and
accepted TASK handoff. [T-00010](00010-TICKET.md) and [T-00011](00011-TICKET.md) must establish the aligned gate and
presentation before final portfolio alignment; each predecessor still includes its own necessary behavior tests.

## Scope

### Included use cases

- A maintainer inventories the current Unit, Integration, and Functional suites and can state what owned behavior,
  package integration, framework wiring, or unrelated concern each test actually proves.
- A reviewer sees a retain/replace disposition with the contract, rationale, replacement diagnostic if any, and
  evidence of equivalent coverage before a wiring-only test is removed.
- A contributor retains meaningful adapter behavior, messaging dispatch/serialization, template-helper integration,
  homepage rendering, and JSON composition evidence at the narrowest useful boundaries.
- A developer runs the canonical gate and obtains both the relevant direct diagnostics and the retained behavior
  suites, without duplicate suite runs or tests of wrappers, tools, infrastructure, or upstream internals.
- A reviewer can reconcile the final test portfolio and source coverage denominator with the prior accepted
  baseline and distinguish intentionally replaced wiring evidence from lost behavior assertions.

### Affected boundaries and documentation

The owned test/fixture portfolio, direct Symfony diagnostics, their inclusion in canonical verification where
required, and test-author guidance are in scope. Preserve the actual contracts behind
[T-00003](00003-TICKET.md) and [T-00007](00007-TICKET.md); neither historical test names nor another application's
policy alone decides whether a test is valuable. Existing owned compiler-pass behavior is not automatically
classified as disposable configuration merely because it uses framework objects.

Document diagnostic commands, scope, failure meaning, and any revised suite classifications. State precisely
which previous mechanisms are superseded while preserving historical records and the stronger Unit requirement.

### Exclusions

Blanket test deletion, pure mock-call-count replacements for observable behavior, weaker assertions, broader source
exclusions, coverage-ignore directives, upstream-behavior duplication, quality-tool self-tests, deliberately invalid
fixtures, new production routes or capabilities solely for testing, package upgrades, and runtime expansion.
No authentication, persistence, browser, or delivery suite is invented for an absent boundary.

## Acceptance Criteria

- [ ] AC-01 — Each current test/fixture has a documented owned contract and retain/replace disposition. The inventory
      distinguishes behavior from wiring rather than using class names, suite labels, or percentages as proof.
- [ ] AC-02 — Every wiring-only replacement has an identified, directly verified equivalent diagnostic before the
      test is retired. Required diagnostics participate in canonical verification rather than becoming optional notes.
- [ ] AC-03 — Meaningful owned-adapter, messaging, templating, homepage, and JSON integration evidence survives at
      proportionate seams, including applicable failure/rejection outcomes; real interactions support journey claims.
- [ ] AC-04 — Exact Unit-only statement coverage, coverage metadata, source scope, and the distinct final Unit
      artifact remain intact. No exclusions, ignores, weaker assertions, or combined percentages mask missing proof.
- [ ] AC-05 — The canonical gate executes required diagnostics and each retained suite once without reintroducing
      product tests of tooling, configuration, test infrastructure, or upstream internals.
- [ ] AC-06 — Documentation identifies the retained contracts, changed classifications, diagnostic commands and
      failure interpretation, and precise supersession of old mechanisms without rewriting historical outcomes.
- [ ] AC-07 — Fresh focused and full-gate results, test counts, Unit denominator, warnings, and justified gaps support
      the final portfolio. Independent Spec and Standards review accepts evidence equivalence, not just fewer tests.

## Verification

During later authorized implementation:

- Inspect assertions, fixtures, production callers, and owned contracts for every proposed disposition. Require
  observable equivalence before removing any check; preserve a test when its behavior has no adequate replacement.
- Run actual replacement diagnostics and the retained focused suites through repository-owned commands. Do not
  create invalid source or product fixtures merely to show a tool detects an error.
- Run `./bin/planning-check`, `git diff --check`, and `./bin/build`. Reconcile the actual phase/suite counts and
  final Unit artifact with T-00010's gate contract and T-00011's changed owned behavior.
- Obtain independent review of the final contract-to-evidence inventory and authorized TASK snapshots, explicitly
  reporting missing categories and why they do or do not apply.

## Applicability

This is test/evidence alignment, not a new business command, query, event, permission, or request-validation
contract. Existing messaging fixtures exercise installed public contracts without introducing production messages.
Security applicability is preservation of existing safe output, service visibility, and secret-safe fixtures/logs;
no new auth behavior is authorized. Validation is direct diagnostics plus owned behavior checks, not tests of
validators or test tooling. Composer dependencies and production package APIs remain unchanged.

## Implementation TASKs

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:children -->

## Completion Notes

Requirements approved and recorded only. No tests have been deleted, diagnostics installed, TASKs created, or
implementation acceptance claimed; the migrated workflow and predecessor outcomes remain outstanding.
