---
id: TASK-NNNNN
ticket: TICKET-NNNNN
kind: feature
title: Brief executable outcome
status: needs-triage
order:
blocked_by:
pr:
authorized: no
review: pending
review_evidence:
verification:
---

# Brief executable outcome

## Outcome

Describe one independently reviewable outcome, normally one PR. Link the accepted requirement and EPIC; only
small standalone bugs/chores may omit the parent. Follow [CONVENTIONS.md](../CONVENTIONS.md). Before verified cutover,
only TASK-00001 may execute under the explicit bootstrap exception; template availability is not authority.

## Scope

- In scope: owned boundaries, package assumptions, documentation, and precise supersession.
- Out of scope: unrelated behavior and separately authorized effects.

## Use cases and contracts

| Use case | Commands | Queries | Events | Expected side effects |
| --- | --- | --- | --- | --- |
| Describe the interaction | Name or justified N/A | Name or justified N/A | Name or justified N/A | Describe effects |

## Validation and permissions

Describe success, rejection/failure, validation, permissions, and security boundaries. Justify non-applicability.
Requirement approval is not execution authority; record the explicit authorization before setting `authorized: yes`.

## Acceptance criteria

- [ ] Observable outcome and applicable failure paths, mapped to parent requirements.

## Verification and evidence

Name focused checks, before/after evidence, and `./bin/build`. For a bug, reproduce it with a failing regression
before repair unless technically impossible with a recorded reason. Verify tools/documents directly, without
tooling tests or invalid fixtures. Record actual counts, warnings, limitations, and evidence references.

## Coordination

Name dependencies, selected checkout/worktree, feature branch/base, and explicit execution authority. Keep SUBTASK
coordination under ignored `.runs/<YYYY-MM-DD>-<slug>/`; the TASK retains the complete outcome.

## Completion notes

Record actual implementation and verification separately from independent Spec and Standards review of the exact
snapshot. A material contributor cannot independently accept the TASK. Use `ready-for-human` while awaiting review;
`done` requires accepted review and verification references. Record findings, disposition, and risks. Publication,
merge, hosted checks, release, deployment, and archive authority are separate. Refresh generated views after edits.
