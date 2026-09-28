# Wayfinder Maps

Wayfinder maps chart uncertain features before handing off to an accepted EPIC, requirement TICKETs, and
explicitly authorized implementation TASKs under [the local lifecycle](../CONVENTIONS.md#wayfinder). Existing
legacy PRD/executable-ticket handoffs retain their authority; no new intermediate PRD is required. A map is an
index of linked decision tickets, not a second source of decisions or execution authority. Start with an active
map's **Frontier**; when none is available, offer to chart a new feature. Implementation follows the sole
[Board](../tickets/BOARD.md) and its bootstrap/cutover guard, not the map's decision frontier.

## Active maps

| Map | Status | Frontier | Boundary |
|---|---|---|---|
| [Symfony AccessControl Starter Application](symfony-access-control-application-map.md) | Active | None; [WF-001](tickets/WF-001-released-package-contract-audit.md) is gated | WF-002 is closed. WF-001 has v0.1.0 baseline evidence and awaits installable AccessControl v0.2.0 reusable schemas plus a delta audit; WF-003 follows. |

Use `_MAP_TEMPLATE.md` and `tickets/_WAYFINDER_TICKET_TEMPLATE.md` for new work. `research/` holds linked
evidence, never a parallel decision record. Archive only through `../../bin/archive-planning` after a map is Closed,
its decisions are Closed, its frontier is empty, and its implementation handoff is linked.
