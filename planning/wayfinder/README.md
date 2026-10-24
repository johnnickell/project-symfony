# Wayfinder Maps

Wayfinder maps chart uncertain features before handing off to an accepted EPIC, requirement TICKETs, and
explicitly authorized implementation TASKs under [the local lifecycle](../CONVENTIONS.md#wayfinder). Existing
legacy PRD/executable-ticket handoffs retain their authority; no new intermediate PRD is required. A map is an
index of linked decision tickets, not a second source of decisions or execution authority. Start with an active
map's **Frontier**; when none is available, offer to chart a new feature. Implementation follows the sole
[Board](../tickets/BOARD.md) and the now-active lifecycle following
[TASK-00001's cutover](../tasks/00001-TASK.md#cutover-and-closeout), not the map's decision frontier. Adoption
closeout does not resolve a Wayfinder decision or authorize application implementation.

## Active maps

| Map | Status | Frontier | Boundary |
|---|---|---|---|
| [Symfony AccessControl Starter Application](symfony-access-control-application-map.md) | Active | [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md), available for research | Released AccessControl v0.8.0 / Common v1.4.0 are installed under the latest-stable request. Audit schema fixes, regression evidence and the full delta; installation does not resolve the [v0.4.0 findings](research/WF-001-v0.4.0-contract-delta-research.md). WF-002 is closed; WF-003 remains blocked by WF-001. |

Use `_MAP_TEMPLATE.md` and `tickets/_WAYFINDER_TICKET_TEMPLATE.md` for new work. `research/` holds linked
evidence, never a parallel decision record. Archive only through `../../bin/archive-planning` after a map is Closed,
its decisions are Closed, its frontier is empty, and its implementation handoff is linked.
