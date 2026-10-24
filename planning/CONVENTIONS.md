# Planning Conventions

## Lightweight work routing

For “What's next?”, `next`, or an unqualified `/ask-matt`, use the routing entry in root AGENTS.md.
This read-only path precedes general orientation; full implementation, review, QA and delivery requirements
still apply when the selected workflow is invoked.

1. Run the documented read-only planning validator once when available. Failure stops selection with the
   actual error and planning-owner handoff; do not refresh views. Without a validator, disclose that limitation
   and check selected record metadata against the authored Board; do not claim generated freshness.
2. Read only the Board's current human decision, active work, and (if no active work) first ready entry.
   Use local section names and authoritative order. Return all active TASKs, not a new winner. Inspect selected
   TASK metadata and its current handoff, plus blocker metadata only as needed. An unresolved contradiction
   is a named input gap, not permission to audit every record or infer acceptance from `done`.
3. Read the Wayfinder index's map-status/frontier inventory. Closed or decision-complete maps need no historical
   reread. For active/open maps, read only current frontier and decision/dependency status or explicit external
   prerequisite evidence needed for availability. Unknown state or an absent frontier needs its owner's input.
   Multiple available maps remain independent choices; no global priority is implied.
4. Use the Board/Roadmap's current planning handoff for unfinished decomposition, including closed maps.
   Verify the named phase in the owning record's current progress/approval section. Map closure, parent status
   and child counts do not prove complete decomposition. If the handoff is missing or contradictory, report
   the missing phase/owner; do not reconstruct it by reading every closed map and descendant.
5. Read canonical review/QA identity and disposition only when they change the selected route. Unresolved
   identity is an evidence handoff, never acceptance. Confirm a suggested skill from supplied capability
   metadata/bindings; load its full instructions and references when invoked, not merely to recommend it.

Prefer heading searches and bounded section reads. Aim to retrieve no more than about 24,000 characters
of routing evidence; stop with a precise unresolved input before exceeding that scope unless the user requests
a deeper audit. Do not read append-only task histories, other worktrees' entire tasks, general architecture,
or all receiving skills to produce a recommendation. Return TASK, WAYFINDER, and relevant PLANNING/HUMAN INPUT;
stop without invoking a workflow or changing files. Missing indexes are reported, not fabricated.

During authorized planning updates, keep current Board/Roadmap handoffs source-linked and concise: target,
phase, pending decision/owner. Keep Wayfinder inventory/frontier pointers current. These are navigation aids,
not another backlog or proof of delivery. Preserve generated sections and all historical decisions.

Routing sources: [TASK Board](tickets/BOARD.md), [Wayfinder inventory](wayfinder/README.md), [Roadmap](ROADMAP.md).

Records own requirements, status, dependencies, and execution priority. Marked tables are generated views, never
another status authority. Authored strategy, decisions, completion evidence, and historical records remain intact.

## Map-wide planning order

For a selected Wayfinder map, finish decisions/grilling and write all approved EPIC destinations before TICKET
planning, then finish all accepted TICKET decompositions before TASK decomposition. Respect authored dependencies
and an explicit narrower human request; this preference does not create dependencies or block unrelated maps.
A grill-mode WF decision remains a Wayfinder interview within its boundary, not automatic EPIC creation.

Current handoffs identify phase, target, remaining scope and decision owner. Neither a closed map, terminal parent
nor a nonempty child table proves decomposition is complete. Missing or contradictory handoffs need owner
reconciliation, not a portfolio-wide historical reconstruction. A closed map can have unfinished downstream
planning without being reopened. Return evidence from research/prototypes to the owning decision first.

Use actual accepted IDs and local templates: decisions via Wayfinder, one approved EPIC via grill, TICKETs via
to-tickets, then TASKs via to-tasks. Stop for each required approval; recommend the next phase without invoking it.
The Board still owns execution, including unrelated active work. Follow [handoffs](../docs/engineering/HANDOFFS.md)
for the supported next command or precise missing human input.

## Bootstrap and cutover

The accepted [T-00008 bootstrap](tickets/00008-TICKET.md#approved-bootstrap-exception) permits only TASK-00001 to
implement the migration. John authorized the current checkout and existing branch. The `lifecycle` field in
[planning/README.md](README.md) controls general activation:

- `bootstrap`: existing legacy authority plus the bounded TASK-00001 exception; no other TASK can enter Ready Frontier.
- `active`: the new workflow below applies to subsequent work. Set it only after TASK-00001's verification and
  independent acceptance, explicit closeout, and a durable `cutover_review` reference. The validator rejects active
  mode unless TASK-00001 is `done` with accepted review. Recording prose or obtaining a green build is not cutover.

The implementation contributor may prepare and verify the mechanism but cannot supply independent acceptance.
After an independent reviewer accepts the exact snapshot, a separately authorized closeout records the acceptance,
marks the TASK done, sets `lifecycle: active` with review evidence, regenerates views, and verifies again. Keep
cutover pending if any condition is missing. Publishing, merging, and archiving remain separate permissions.

## Automatic parent completion

When a TASK becomes `done` or `wontfix`, complete eligible parent TICKETs and then EPICs in the same operation.
Count live and archived children. A live, non-terminal parent with at least one child closes when every child
is terminal: use `wontfix` if every child is `wontfix`, otherwise `done`. Parents without children or with an
unfinished child remain open. Preserve already-terminal and archived parents.

Child acceptance and intentional `wontfix` decisions remain with the child records. Parent completion requires
no separate assessment, independent review, QA, confirmation, or skill invocation. Record any remaining work as
an unfinished child rather than a separate parent-closeout gate. Parent status does not assert review, merge,
release, deployment or publication, and completion never archives records automatically.

Run `./bin/planning-check --write` during completion; it closes eligible parents and refreshes views.
Then run the read-only `./bin/planning-check`. Read-only validation never writes completion metadata.

This rule covers current EPICs and requirements TICKETs, including preserved `T-` requirement IDs. Legacy PRDs,
legacy executable tickets and the separately accepted planning cutover retain their existing contracts.


## Hierarchy and identities

| Record | Role | Path / new ID |
| --- | --- | --- |
| EPIC | Destination, accepted scope, exclusions | `epics/NNNNN-EPIC.md` / `EPIC-NNNNN` |
| TICKET | Requirements, use cases, observable acceptance; may span PRs | `tickets/NNNNN-TICKET.md` / `TICKET-NNNNN` |
| TASK | Independently executable/reviewable slice, normally one PR | `tasks/NNNNN-TASK.md` / `TASK-NNNNN` |
| SUBTASK | Temporary coordination under a TASK | Ignored `.runs/tasks/<TASK-ID>/runs/<run-id>/` |
| Legacy PRD | Continuing or historical requirements | Existing `specs/NNNNN-PRD.md` / `PRD-NNNNN` |
| ADR | Architecture decision | `adr/NNNN-description.md` |
| Wayfinder | Investigation maps and WF decisions, not executable work | `wayfinder/`, `WF-NNN-description.md` |

Each level has a five-digit numeric sequence across live and archived records. Never reuse numbers, fill gaps by
assumption, or renumber existing identities. Ticket numbers share one sequence across preserved `T-` and new
`TICKET-` names: after T-00012 the next unused ticket number is 00013, not TICKET-00001. Filenames must match IDs.
Copy the directory's `_…_TEMPLATE.md`; templates are not records. Inspect live and archive files before allocation.

T-00001 through T-00007 remain PRD-parented legacy executable tickets. T-00008 through T-00012 remain epic-parented
requirements with their original IDs and meanings; they are not TASKs. Parent metadata and the explicit compatibility
set distinguish them. New requirements use `TICKET-`, not an open-ended second `T-` naming scheme. Legacy PRDs are
not required for new work and are not converted, reparented, closed, or archived by this migration.

T-00004 continues under legacy rules and PRD-00002. Its Fight Common 2.0 contract, deprecation-removal inventory,
and migration guide remain prerequisites. Keep `needs-info` until the owning evidence exists. Its future inventory
and bounded execution require explicit local authorization; it is not silently eligible on the TASK Board and
needs no successor merely to close this migration. No new legacy executable tickets are created.

## Metadata

Frontmatter uses unquoted, single-line scalar `key: value` fields; blank values are supported. No nested YAML,
multiline scalars, duplicate keys, or inline comments. Titles may contain colons. Metadata is local to each record.

- EPIC: `id`, `title`, `status`, `target`, `approved: yes|no` (missing approval means no).
- Requirement: `id`, `epic`, `title`, `status`, `approved: yes|no`, `blocked_by` (requirement IDs only).
- TASK: `id`, `ticket`, `kind`, `title`, `status`, `order`, `blocked_by`, `pr`, `authorized`, `review`,
  `review_evidence`, `verification`. Parent requirements may use their preserved `T-` identity. `kind` is
  `feature`, `bug`, or `chore`; only small standalone bugs/chores may leave `ticket` blank.
- Legacy tickets require their actual `prd`; legacy PRDs may retain an existing optional `epic`.

`approved: yes` records human acceptance of the requirement/destination, not permission to execute. TASK
`authorized: yes` records explicit execution authority and a chosen checkout/worktree in its Coordination section.
Use `review: pending|revise|accepted`; `accepted` requires a durable `review_evidence` reference. `verification`
references the TASK's actual command/results evidence. Evidence fields use a relative Markdown path with an optional
heading anchor, or a full HTTPS URL (for example `00001-TASK.md#implementation-evidence`). Local files/anchors are
validated; remote evidence is not fetched. Keep new detailed reports ignored; use existing durable references or
authorized HTTPS report locations for tracked metadata, not clone-breaking links to ignored files or new acceptance
narratives. Do not invent proof or treat metadata as independent review.
`pr` is blank or a full HTTPS pull-request URL; it does not assert live merge status.

`order` is blank or a positive integer, lower first. Unranked work comes last; IDs break ties for deterministic
presentation, not a mandate to start another task. `blocked_by` is comma-separated and stays recorded after
completion. TASKs depend only on TASKs; requirements depend on requirements; legacy tickets on legacy tickets.
Unfinished prerequisites are derived from nonterminal blockers, including parent requirement blockers. No cycles,
unknown references, duplicate identities/numbers, or wrong parent types are accepted.

## Readiness, completion, and review

| Status | Meaning |
| --- | --- |
| `needs-triage` | Scope or ownership is not classified |
| `needs-info` | Required evidence or a decision is missing |
| `ready-for-agent` | Scope is decision-complete; execution only if authority, accepted parents, and prerequisites permit |
| `ready-for-human` | Human judgment, independent review, or an external action is next |
| `in-progress` | Authorized implementation/revision or parent planning is underway |
| `done` | Accepted outcome and required verification are complete |
| `wontfix` | Explicitly closed without delivering the outcome |

Blocking is derived, never a stored status. An unapproved parent, closed parent, unfinished requirement/TASK blocker,
missing execution authority, or pending general cutover prevents TASK selection. Active implementation with those
gates fails validation. Requirements themselves never enter the execution frontier.

TASK completion requires fresh local verification and independent Spec and Standards acceptance of its exact
branch/base/head and relevant working-tree state under [Review](../docs/engineering/REVIEW.md). Canonical reports
and detailed execution evidence use [ignored scratch](../docs/engineering/SCRATCH.md), not new tracked acceptance
narratives. Preserve existing evidence fields and validator requirements; do not fabricate a link or mark done
when required evidence cannot be resolved. A future planning-store migration remains separately authorized.
[QA](../docs/engineering/QA.md) governs behavioral applicability and [Landing](../docs/engineering/LANDING.md)
governs accepted-candidate versus administrative-closeout proof. A material contributor cannot accept their own work. One
independent reviewer may perform both passes. Record criterion-to-evidence mapping, findings/disposition, warnings,
limitations, and remaining risks. Use `ready-for-human` for implementation awaiting independent review; do not
prematurely mark it done to satisfy a pre-PR checklist. Revisions use `review: revise` until reaccepted.

Apply [Automatic parent completion](#automatic-parent-completion) when children become terminal; no additional
parent assessment is required. `wontfix` remains intentional closure without implementation. Terminal parents cannot contain unfinished children. Legacy
terminal evidence is preserved, not retroactively forced into new review fields. None of these statuses implies
hosted success, PR publication, merge, release, or deployment.

## One Board and generated views

[planning/tickets/BOARD.md](tickets/BOARD.md) remains the sole Board at its stable, historically linked path. It
now projects TASK execution, not requirement priority. Do not create a competing `tasks/BOARD.md`.

For an unqualified "What's next?", return the authored **Now** human decision and the current **Active Work** TASK;
if none is active, return the first eligible TASK in **Ready Frontier**. If none is eligible, say so. Do not start
a second TASK merely because its order/ID sorts earlier. Waiting, human, information, triage, and closed sections
are generated from TASK metadata. A separate legacy section keeps T-00004 visible without turning it into a TASK.

Generated `<!-- planning:NAME -->` / `<!-- /planning:NAME -->` sections include Board rows, live and archive indexes,
live EPIC/requirement child tables, and Roadmap EPIC/progress frontiers. Edit the source records, not generated rows.
The Roadmap's planning frontier surfaces parents needing decomposition, with blockers; it is
not executable work. Author **Now**, strategy, Wayfinder review pointers, and completion prose outside markers.
Historical generated tables inside archived records are frozen evidence; archive indexes remain live projections.

```bash
./bin/planning-check --write
./bin/planning-check
```

Write mode validates records and local Markdown links before refreshing marked sections. Read-only mode rejects
stale views and must not rewrite files. `./bin/build` continues to use read-only validation. Repeated generation
must be unchanged. Missing/duplicate markers fail rather than replacing authored sections. Verify tooling directly
on the real portfolio; do not add tooling tests, invalid fixtures, or PHPUnit tests of planning documents.

## Wayfinder

Maps remain pre-implementation investigation, not requirements or execution records. Use `_MAP_TEMPLATE.md` with
label, Active/Closed status, destination/done condition, linked decisions, decision table and dependencies, one
Frontier, fog, and exclusions. Preserve the accepted WF type/mode/status and external Gate conventions. A map is
an index: material decisions live in linked WF tickets, research in `wayfinder/research/`.

After cutover, new handoffs link an accepted EPIC, requirement TICKETs, then approved TASKs; no new intermediate
PRD is mandatory. Existing map and closed-decision references retain their historical meaning. The Wayfinder README
is the continuity index. The Board's authored Wayfinder Review pointer may name only a concrete unblocked grillable
frontier; it never overrides that map's authority or the TASK execution frontier.

## Explicit-only archive operation

Never archive as a completion side effect. Run the owning command only after an explicit archive request, review
its dry run, then apply that exact selection. Do not move files by hand.

| Selection | Command | Destination |
| --- | --- | --- |
| TASK | `./bin/archive-planning tasks TASK-00001 … [--apply]` | `tasks/archive/` |
| Requirement or legacy ticket | `./bin/archive-planning tickets TICKET-00013 … [--apply]` (preserved `T-` IDs also valid) | `tickets/archive/` |
| Legacy PRD | `./bin/archive-planning specs PRD-00001 … [--apply]` | `specs/archive/` |
| EPIC | `./bin/archive-planning epics EPIC-00001 … [--apply]` | `epics/archive/` |
| Wayfinder map | `./bin/archive-planning wayfinder map-name [--apply]` | Existing Wayfinder archive directories |

Selected records must be terminal and have terminal descendants across live and archive directories. TASK `done`
also requires its accepted review and verification metadata. A Closed Wayfinder map needs all linked decisions
Closed, an empty frontier, and its implementation handoff. Moves preserve IDs and evidence and repair owned local
Markdown links; generation refreshes live views and archive indexes. Inspect the diff, regenerate, and validate
before committing. TASK-00001 authorizes eligibility implementation, not an archive operation, even a dry run.

## Branches and delivery synchronization

Create feature branches from `develop`; for new TASKs prefer `feature/task-NNNNN-<slug>`. Preserve already authorized
branches. Explicitly choose main checkout or isolated worktree before execution. Keep coordination scratch under
the categorized ignored [scratch layout](../docs/engineering/SCRATCH.md); never commit it or alter unrelated work.

Before a commit or PR:

1. Record actual results and outstanding review in ignored task evidence; keep status truthful and mark done only
   after independent acceptance and required verification. Do not append new tracked acceptance narratives.
2. Update parent progress and the Roadmap when outcomes change, without prematurely closing requirements.
3. Recalculate the human decision, active work, and eligible frontier. Retain completed dependency edges.
4. Refresh generated views; update affected authored Wayfinder continuity without inventing new decisions.
5. Run `./bin/planning-check --write`, `./bin/planning-check`, `git diff --check`, and the canonical `./bin/build`.
6. Inspect and commit only owned changes; publication and merge require separate authority.
