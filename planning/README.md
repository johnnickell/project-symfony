---
lifecycle: active
cutover_review: tasks/00001-TASK.md#independent-acceptance
---

# Planning

This directory owns the durable planning record. Read [CONVENTIONS.md](CONVENTIONS.md) for metadata, acceptance,
legacy coexistence, generation, routing, and explicit-only archive rules.

- `epics/`: strategic destinations.
- `tickets/`: requirements for new work; preserved PRD-parented legacy executable tickets remain addressable.
- `tasks/`: bounded implementation and independent acceptance, normally one PR each.
- [tickets/BOARD.md](tickets/BOARD.md): the sole human and TASK execution Board, at its stable historical path.
- `specs/`: continuing/historical legacy PRDs, not a mandatory new-work layer.
- `adr/`, `agents/`, and `wayfinder/`: architectural decisions, focused guidance, and investigation authorities.
- [ROADMAP.md](ROADMAP.md): authored strategy with generated EPIC and planning-frontier views.

## Cutover control

The lifecycle is **active** following John's 2026-09-27 authorization to finish adoption, the independently accepted
[TASK-00001](tasks/00001-TASK.md#independent-acceptance), and its
[cutover verification and closeout](tasks/00001-TASK.md#cutover-and-closeout). PR #14's implementation was already
merged; this follow-up records the previously deferred activation. New work uses EPIC -> TICKET -> TASK without
another bootstrap approval. Approved requirements still need separately scoped and authorized implementation TASKs.

The frontmatter records the durable independent acceptance reference. TASK-00001 must be done with accepted review
evidence; the validator rejects activation otherwise. The original
[bootstrap exception](tickets/00008-TICKET.md#approved-bootstrap-exception) remains historical authority, not an
unfinished gate. See [the cutover procedure](CONVENTIONS.md#bootstrap-and-cutover).

## Maintenance

Copy local templates and inspect live/archive IDs before allocating records. Record scope, use cases, exclusions,
validation, permissions, and observable acceptance; justify non-applicable concerns. After metadata edits run
`./bin/planning-check --write` then `./bin/planning-check`. Ordinary validation and the build never rewrite views.
Edit authored prose outside generated markers. Keep scratch under ignored `.runs/<YYYY-MM-DD>-<slug>/`.

Archive only on an explicit request through `./bin/archive-planning`, dry run before apply. Do not automatically
convert, renumber, close, move, or archive legacy records. T-00004 remains `needs-info` under PRD-00002 until its
owning package supplies the accepted migration prerequisites.
