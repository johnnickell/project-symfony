---
lifecycle: bootstrap
cutover_review:
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

The frontmatter's `lifecycle: bootstrap` permits only the explicitly authorized
[TASK-00001](tasks/00001-TASK.md) under [T-00008's exception](tickets/00008-TICKET.md#approved-bootstrap-exception).
John authorized the current checkout and existing branch. Implementation of migration tooling is not independent
acceptance or general lifecycle activation.

After verified independent acceptance and authorized closeout, set `lifecycle: active` and record the durable
review reference in `cutover_review`. TASK-00001 must be done with accepted review evidence; the validator rejects
activation otherwise. Missing acceptance keeps bootstrap mode and the human decision visible. The implementation
contributor cannot supply that acceptance. See [the cutover procedure](CONVENTIONS.md#bootstrap-and-cutover).

## Maintenance

Copy local templates and inspect live/archive IDs before allocating records. Record scope, use cases, exclusions,
validation, permissions, and observable acceptance; justify non-applicable concerns. After metadata edits run
`./bin/planning-check --write` then `./bin/planning-check`. Ordinary validation and the build never rewrite views.
Edit authored prose outside generated markers. Keep scratch under ignored `.runs/<YYYY-MM-DD>-<slug>/`.

Archive only on an explicit request through `./bin/archive-planning`, dry run before apply. Do not automatically
convert, renumber, close, move, or archive legacy records. T-00004 remains `needs-info` under PRD-00002 until its
owning package supplies the accepted migration prerequisites.
