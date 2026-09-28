# Local issue tracking

Repository-local records are the source of truth: EPIC destinations, TICKET requirements, and TASK implementation
and acceptance. Use [the conventions](../CONVENTIONS.md) for the bootstrap guard, preserved legacy identities,
metadata, and closeout rules. Umbrella references are provenance, not another status system.

Each requirement names use cases, public-package assumptions, affected adapters, verification, documentation,
and exclusions. Each TASK delivers bounded reviewable scope, normally one PR, with explicit execution authority
and checkout/worktree choice. No requirements record enters the execution frontier.

Refresh marked views through `./bin/planning-check --write`, then run the read-only check. The sole
[Board](../tickets/BOARD.md) lives at its stable historical path. Keep authored human decisions and actual evidence
truthful while checks or independent acceptance remain pending; never label unverified work done.
