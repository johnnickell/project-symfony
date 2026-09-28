# Triage States

Use the [canonical metadata and lifecycle rules](../CONVENTIONS.md#readiness-completion-and-review) at each level.

- `needs-triage`: scope or ownership is unclassified.
- `needs-info`: required evidence or a decision is missing.
- `ready-for-agent`: scope is settled; authority, accepted parents, and dependencies still govern execution.
- `ready-for-human`: human judgment, independent review, or an external action is next.
- `in-progress`: authorized implementation/revision or parent planning is underway.
- `done`: accepted outcomes and required verification are complete.
- `wontfix`: explicitly closed without implementation; not evidence of satisfied requirements.

Blocking is derived, never stored as a status. Preserve completed dependency edges. Requirements approval alone
does not make them executable. TASK `done` requires verification and independent acceptance; hosted checks,
publication, merge, release, deployment, and archiving remain separate.
