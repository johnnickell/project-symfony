---
id: TICKET-00013
epic: EPIC-00002
title: Require a Patched Compatible HttpFoundation Baseline
status: in-progress
approved: yes
blocked_by:
---

# Require a Patched Compatible HttpFoundation Baseline

## Outcome

Remove the starter's dependency exposure to CVE-2026-108082 while retaining its existing behavior. Adopt the latest
compatible stable HttpFoundation release available at implementation time and prevent dependency resolution below
8.1.9, which includes both the security fix and its whitespace-separator regression correction.

John approved one cohesive requirement TICKET covering remediation and verification under
[EPIC-00002](../epics/00002-EPIC.md). This approval permits TASK planning, not implementation or publication.
Use [local conventions](../CONVENTIONS.md) for subsequent scope acceptance and execution authority.

## Use cases and contracts

- A maintainer installs the locked dependencies and receives a patched HttpFoundation version. During an authorized
  dependency update, root constraints prevent a return to versions below 8.1.9 rather than relying on the lock alone.
- A maintainer selects the latest stable version compatible with the existing PHP/Symfony application baseline,
  identifies any necessary companion dependency changes, and preserves unrelated package selections. If the repair
  requires broader compatibility changes, stop for John's scope decision rather than weakening constraints or checks.
- HTTP requests retain correct header parsing, including whitespace separators, and existing HTML and explicit web
  JSON behavior. The bare-Kernel console remains unaffected. Safe bounded verification must not load-test or attack
  shared services or production.
- A reviewer can establish that the installed and locked versions match the selected baseline and that a fresh
  locked Composer audit no longer reports CVE-2026-108082 / PKSA-xmwy-rmx5-m7h4. Other findings remain disclosed;
  audit suppression or ignoring this advisory is not remediation.

The security boundary is unauthenticated request-header parsing, owned by Symfony's public package implementation.
Symfony integration and dependency constraints belong to this application; vendor source and Fight package contracts
remain package-owned. No new application commands, queries, events, permissions, or request-validation policies are
introduced. Composer operations and verification commands are maintenance actions, not new application use cases.

## Scope and exclusions

Include the HttpFoundation dependency baseline, necessary companion dependency selections, a durable resolution floor,
focused safe compatibility verification, and any dependency/preparation guidance directly affected by this repair.
Preserve Symfony composition, public Fight contracts, HTML, explicit web JSON middleware, bare-Kernel console behavior,
and all existing verification strength. Consume upstream fixes through Composer without copying or editing vendor code.

Exclude unrelated upgrades, framework-major migrations, runtime/topology changes, new application features, a broader
security audit, and ongoing dependency-security gate automation. Do not weaken coverage, tests, static checks, or
package compatibility to make the upgrade pass. Production remediation, publication, merge, release, and deployment
remain separate and are not authorized here. Clearing this advisory does not imply globally clean security.

## Acceptance criteria

- [ ] AC-01 — Fresh release and compatibility evidence identifies the latest compatible stable HttpFoundation version
      at implementation time, at least 8.1.9; locked and installed package identities match that selection.
- [ ] AC-02 — Root Composer constraints prevent resolution below 8.1.9, not merely selection in the current lock.
      Necessary companion dependency changes are individually justified; unrelated selections remain unchanged.
- [ ] AC-03 — A fresh locked Composer audit no longer reports CVE-2026-108082 / PKSA-xmwy-rmx5-m7h4 without advisory
      suppression. Any remaining findings, audit failures, or unavailable advisory sources are reported accurately.
- [ ] AC-04 — Safe bounded checks demonstrate the corrected relevant header-parsing behavior, including whitespace
      separators, and preserve existing HTML, explicit web JSON middleware, and bare-Kernel console behavior.
- [ ] AC-05 — The canonical `./bin/build` passes with exact Unit-only owned-production statement coverage and meaningful
      retained Integration/Functional journeys unchanged in strength. Dependency tooling is verified directly through
      its owning commands, not product tests or invalid tooling fixtures.
- [ ] AC-06 — Independent Spec and Standards review and applicable behavioral QA evaluate the eventual TASK snapshot,
      with actual checks, counts, coverage, warnings, and limitations in ignored evidence under existing local rules.

## Dependencies and verification

No requirement blockers are identified. This work does not wait on EPIC-00001's gate redesign, T-00004's package
migration prerequisites, or the AccessControl Wayfinder investigation. TASK-00003's PHP-only security repair is a
separate completed boundary, not proof that this advisory is fixed.

During later authorized implementation, use repository-owned Composer commands for package/platform identity,
constraint resolution and locked audit checks; use bounded package diagnostics for relevant parsing behavior and
retained journeys for application compatibility. Recheck the latest compatible stable release instead of assuming
8.1.9 remains latest. Run the canonical gate and obtain independent acceptance and applicable QA without treating a
green build as semantic review. TASK planning determines executable scope and regression-first checks where feasible;
it must preserve safe execution and avoid duplicating upstream implementation tests without an owned contract.

## Implementation TASKs

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 4 | [TASK-00004 — Upgrade HttpFoundation and Enforce the Patched Baseline](../tasks/00004-TASK.md) | [TICKET-00013 — Require a Patched Compatible HttpFoundation Baseline](00013-TICKET.md) | ready-for-human | — | — |
<!-- /planning:children -->

## Progress and closeout

John accepted the single-TICKET decomposition and then approved one implementation TASK,
[TASK-00004 — Upgrade HttpFoundation and Enforce the Patched Baseline](../tasks/00004-TASK.md), covering all six
acceptance criteria. Requirement and TASK decomposition are complete for EPIC-00002. John selected the main checkout
and invoked Build TASK-00004, authorizing implementation and owned commits; no other TASK slices remain to be planned.
The implementation handoff awaits independent Review and applicable QA, coordinated by Build.
Apply [automatic parent completion](../CONVENTIONS.md#automatic-parent-completion) when authorized child TASKs become
terminal; no additional parent review gate is introduced. Publication, human merge, release, and deployment remain
separate from requirement completion.

Next: independent `/skill:review TASK-00004`, coordinated by the active Build run.
