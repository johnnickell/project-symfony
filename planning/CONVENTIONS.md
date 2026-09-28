# Planning Conventions

Records own requirements, status, dependencies, and execution priority. Marked tables are generated views, never
another status authority. Authored strategy, decisions, completion evidence, and historical records remain intact.

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

## Hierarchy and identities

| Record | Role | Path / new ID |
| --- | --- | --- |
| EPIC | Destination, accepted scope, exclusions | `epics/NNNNN-EPIC.md` / `EPIC-NNNNN` |
| TICKET | Requirements, use cases, observable acceptance; may span PRs | `tickets/NNNNN-TICKET.md` / `TICKET-NNNNN` |
| TASK | Independently executable/reviewable slice, normally one PR | `tasks/NNNNN-TASK.md` / `TASK-NNNNN` |
| SUBTASK | Temporary coordination under a TASK | Ignored `.runs/<YYYY-MM-DD>-<slug>/` only |
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
validated; remote evidence is not fetched. Do not invent proof or treat metadata as independent review.
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
branch/base/head and relevant working-tree state. A material contributor cannot accept their own work. One
independent reviewer may perform both passes. Record criterion-to-evidence mapping, findings/disposition, warnings,
limitations, and remaining risks. Use `ready-for-human` for implementation awaiting independent review; do not
prematurely mark it done to satisfy a pre-PR checklist. Revisions use `review: revise` until reaccepted.

A TICKET closes only when its required outcomes are satisfied by accepted TASKs and an explicit closeout review.
An EPIC closes on satisfied requirement outcomes. Terminal children trigger a closeout decision, not automatic
completion: `wontfix` is not delivered acceptance. Terminal parents cannot contain unfinished children. Legacy
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
The Roadmap's planning frontier surfaces parents needing decomposition or explicit closeout, with blockers; it is
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
ignored `.runs/<YYYY-MM-DD>-<slug>/`; never commit it or alter unrelated work.

Before a commit or PR:

1. Record the TASK's actual results and outstanding review honestly; only mark done after independent acceptance.
2. Update parent progress and the Roadmap when outcomes change, without prematurely closing requirements.
3. Recalculate the human decision, active work, and eligible frontier. Retain completed dependency edges.
4. Refresh generated views; update affected authored Wayfinder continuity without inventing new decisions.
5. Run `./bin/planning-check --write`, `./bin/planning-check`, `git diff --check`, and the canonical `./bin/build`.
6. Inspect and commit only owned changes; publication and merge require separate authority.
