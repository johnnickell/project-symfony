# Planning Authority

This directory is the canonical planning surface for the Fight Symfony Starter. It is intentionally independent of
Fight Common's umbrella records: this repository owns Symfony implementation scope, status, acceptance, build
evidence, and documentation updates.

| Surface | Authority |
| --- | --- |
| `specs/` | Product requirements and enduring acceptance decisions. |
| `tickets/` | Executable vertical slices, their status, blockers, and recommended order. |
| `ROADMAP.md` | Capability sequence, not ticket status. |
| `agents/` | Focused architecture, tracking, and triage instructions. |

`tickets/BOARD.md` is the operational answer to “what is next?”; ticket files remain canonical for their own
acceptance criteria and blocking edges. Validate this structure with `./bin/planning-check`.
