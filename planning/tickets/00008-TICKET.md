---
id: T-00008
epic: EPIC-00001
title: Adopt the Three-Level Planning Lifecycle
status: in-progress
blocked_by:
approved: yes
---

# Adopt the Three-Level Planning Lifecycle

## Outcome

Separate strategic destinations, cohesive requirements, and independently executable work through an
EPIC -> TICKET -> TASK lifecycle, without losing the meaning or addressability of existing planning records.
Contributors can identify the next authorized action without confusing accepted requirements with executable work.

## Approval and execution boundary

John approved this requirement area as part of the five-ticket decomposition of
[EPIC-00001](../epics/00001-EPIC.md). This is a requirement TICKET, not a legacy executable ticket or a TASK,
and may require multiple implementation slices. No new PRD layer is required.

The single-TASK decomposition and bootstrap exception below are approved. John subsequently authorized execution
in the current checkout on the same branch. This requirement remains non-executable; TASK-00001 owns the work.
Implementation has independent Spec and Standards acceptance; verified cutover and explicit closeout remain outstanding.

## Scope

### Included use cases

- A maintainer approves an EPIC, decomposes it into requirement TICKETs, and approves separately scoped TASKs for
  implementation. A TICKET may span multiple PRs; a TASK normally produces one independently reviewable PR.
- A contributor follows templates, identifiers, parent relationships, dependencies, and level-specific readiness
  and completion rules without relying on another repository's tooling or private documentation.
- An operator asks "What's next?" and receives the current human decision and the first eligible execution TASK
  from one authoritative board after cutover, not competing old/new execution frontiers.
- An independent reviewer attaches acceptance to a TASK's exact implementation snapshot. TICKET and EPIC progress
  reflects accepted outcomes without implying publication, merge, release, or deployment.
- A reader follows a legacy PRD or executable ticket and finds its original identity, meaning, evidence, and links.
  New requirement records, including T-00008 through T-00012, cannot be mistaken for legacy executable tickets.
- The migration itself follows an explicitly authorized bootstrap path that does not require absent TASK tooling
  or reinterpret this requirement record as an implementation assignment.

### Affected boundaries and documentation

Local planning conventions, agent/contributor routing, templates, identifiers, indexes, parent/dependency
validation, execution-board projection, and completion guidance change together. Existing explicit-only archive
rules remain intact; future archive eligibility must distinguish legacy PRDs from the new hierarchy without
moving records as a migration side effect. Repository wrappers remain the entrypoints for validation.

### Exclusions

- Business behavior, package/version changes, gate redesign, runtime expansion, new application APIs, or managed
  agent execution machinery.
- Automatic legacy conversion, renumbering, identity reuse, deletion, closure, archiving, or branch renaming.
- Creating implementation TASKs merely by accepting or recording these requirements.

## Dependencies and transition

No requirement dependency blocks this migration. Independent review for the bootstrap follows the accepted epic
requirements; it does not wait for T-00009's later documentation work. After verified cutover, new implementation
uses separately approved TASKs linked to requirement TICKETs.

The approved disposition is to keep [T-00004](00004-TICKET.md) under explicit legacy rules, preserving its
`needs-info` state, unmet owning-package gate, and PRD-00002 parent authority. No successor is needed for this
migration. Recording this choice does not declare package artifacts available or make T-00004 executable.

### Approved bootstrap exception

John approved one atomic implementation scope:
[TASK-00001 — Activate the Three-Level Planning Lifecycle](../tasks/00001-TASK.md), with no dependency blockers.
The first approval authorized planning-record creation only. John subsequently approved execution in the current
checkout on `feature/engineering-alignment-planning`; that bounded bootstrap now proceeds as follows:

- Introduce the local [TASK template](../tasks/_TASK_TEMPLATE.md) and TASK-00001 as a bounded exception to current
  conventions. This does not authorize additional pre-cutover TASKs or change the general lifecycle.
- Use `ticket: T-00008`, the parent's existing canonical identity. Do not invent `TICKET-00008`, rename this record,
  or convert it into the bootstrap TASK. Require genuine TASK-aware validation, not a legacy-only pass.
- Keep the existing [Board](BOARD.md) as the sole frontier at its stable path. During bootstrap, only authorized
  TASK-00001 may execute; generated sections reflect its actual state, not permission for downstream work.
- Under the recorded execution authority, deliver definitions, templates, metadata validation, genuine view generation,
  routing/caller updates, and legacy/archive coexistence together. The bootstrap's bounded authority must be
  recorded before implementation; it does not depend on the new workflow already existing.
- Require fresh direct verification, the canonical build, and independent Spec and Standards acceptance before
  declaring cutover. Until then, current conventions plus this explicit exception apply. Completion of the
  migration does not authorize downstream implementation, publication, merge, or archive operations.

## Implementation TASK

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| 1 | [TASK-00001 — Activate the Three-Level Planning Lifecycle](../tasks/00001-TASK.md) | [T-00008 — Adopt the Three-Level Planning Lifecycle](00008-TICKET.md) | ready-for-human | — | — |
<!-- /planning:children -->

## Acceptance Criteria

- [ ] AC-01 — Local guidance defines EPIC outcomes, TICKET requirements, and TASK implementation scope, with
      readiness/completion rules and explicit separation of requirement approval, review, hosted status, and merge.
- [ ] AC-02 — The bootstrap plan and execution authority are accepted before implementation; the verified cutover
      identifies when new records and routing use the migrated model without circular tooling prerequisites.
- [ ] AC-03 — Templates, identifiers, parent/dependency validation, indexes, and routing support the new hierarchy;
      the actual portfolio validates without dropping legacy records or weakening dependency checks.
- [ ] AC-04 — One authoritative execution frontier selects eligible TASKs after cutover. Normal approved work
      reaches that frontier; missing acceptance or unfinished blockers cannot be treated as executable work.
- [ ] AC-05 — Legacy PRDs and executable tickets retain their original IDs, meanings, links, and evidence.
      Transitional requirement records remain requirements, not silently relabeled TASKs.
- [ ] AC-06 — T-00004 has a documented continuation or explicitly linked successor that preserves its package gate
      and needs-info boundary. Migration completion does not force its completion or its parent's retirement.
- [ ] AC-07 — Contributor/agent guidance and the actual planning commands agree with the new model; no duplicate
      status authority, implicit archive operation, new mandatory PRD layer, or private reference is required.
- [ ] AC-08 — Direct planning/routing checks, the canonical build, and independent review establish the delivered
      migration. Product tests of planning tooling and deliberately invalid fixtures are not introduced.

## Verification

During later authorized implementation:

- Run `./bin/planning-check` on the real mixed legacy/new portfolio and inspect preserved links, unique identities,
  parents, dependency ordering, and completion projections directly.
- Walk an approved new-work request and a genuine pending-acceptance or unfinished-blocker case through the routing
  instructions. Record the exact selected frontier and why unavailable work is excluded; do not fabricate fixtures.
- Inspect the before/after portfolio for unintended relabeling, closure, movement, or loss of legacy authority.
- Run `git diff --check` and `./bin/build`; retain fresh results and limitations, then obtain independent Spec and
  Standards review of the authorized implementation snapshot.

## Applicability

No business command, query, event, permission, or request-validation contract is added. Validation here concerns
planning metadata, relationships, and authority. Security concerns are permission to execute/publish, preservation
of unrelated work, and secret-safe evidence; no authentication or authorization runtime is introduced. Public Fight
package contracts and installed dependencies remain unchanged; no production application adapter is affected.

## Completion Notes

TASK-00001 is authorized in the current checkout on the existing branch. It implements typed validation, marked
view generation, one stable Board, and legacy-aware archive eligibility. T-00004's explicit legacy continuation is
recorded without modifying its evidence or status. See the TASK for fresh verification and limitations.
Independent re-review accepted the corrected R1 implementation; TASK-00001 records the exact snapshot, criterion
coverage and limitations. John requested PR publication through landing. Guarded cutover and explicit requirement
closeout remain pending; technical acceptance and publication do not activate the lifecycle. This requirement is not done.
