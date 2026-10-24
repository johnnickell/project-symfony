# Runtime files and execution scratch

Application-generated files and persistent local runtime state belong in ignored `var/`. Application runtime
must never depend on `.runs/`. Tool caches remain under `var/cache/<tool>` in this Symfony project; do not move
existing configured caches merely to match another application's paths.

## Top-level execution categories

Use these directories directly beneath ignored `.runs/`, creating only those with real contents:

| Directory | Owner/content |
| --- | --- |
| `archive/<batch>/` | Inactive legacy execution evidence and reversible relocation manifest |
| `build/<TASK-ID>/` | Build coordinator ledger, lock and fresh role attempts |
| `tasks/<TASK-ID>/runs/<run-id>/` | Implementation, verification, probes and delivery evidence |
| `reviews/<TASK-ID>/` | Canonical independent technical review and retained history |
| `qa/<TASK-ID>/` | Canonical independent behavioral QA, runs and captures |
| `notes/<subject>/` | Research or working notes, not application runtime |
| `handoffs/<subject>/` | Cross-task/package handoffs |
| `prototypes/<subject>/<experiment>/` | Disposable design evidence |
| `worktrees/` | Explicitly chosen registered Git worktrees, managed through Git |

No new loose date/task folders at `.runs/` root, no separate `land/`, and no compatibility symlinks. Unique run IDs
prevent overwrites. Canonical Review/QA live at the base checkout resolved through Git metadata; only their
independent owners replace them. Work and Land may read but never rewrite verdicts. Scratch stays untracked.

## Archive and relocation

Archive only on explicit authority, after inspecting a dry run. Select exact inactive owned directories; inspect
registered worktrees, open files/processes, mounts, locks, symlinks and destination collisions. Preserve active,
shared or ambiguous resources, canonical reports and credentials. A terminal TASK alone is not liveness evidence.
Do not move or delete a running service's state to make the directory tidy.

Before moving, retain an ignored manifest with old/new relative paths, file SHA-256 digests, file modes, empty
directories and exclusions. Revalidate immediately before mutation; move without overwriting, verify inventories
and hashes afterward, and retain a reversible mapping. No recursive deletion, force-unmount or broad pruning.

Current callers must follow the new path. Original historical receipts, commands, snapshots and independent
reports remain byte-preserved: resolve an old `.runs/<legacy>/...` path through the `moves` entries in
`.runs/archive/*/manifest.json`, then append the unchanged suffix. Inspect that batch's verification before treating
it as present. Do not rewrite signed/history evidence or create an old-path alias. Unavailable local artifacts
remain unavailable on fresh clones; an ignored archive is not a portable backup or publication.

This operation moves ignored execution evidence only. Tracked planning records retain the separate explicit
`./bin/archive-planning` dry-run/apply contract; adopting this layout does not archive or remove them.
