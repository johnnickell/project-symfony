# Handoffs and next commands

End useful work with `Next:` and a supported command naming the real subject, or the exact missing human decision.
Recommendations never invoke a workflow or grant authority. Keep wording readable, evidence above the next action,
and implementation, independent acceptance, QA, publication and merge separate.

## Routing

[Lightweight routing](../../planning/CONVENTIONS.md#lightweight-work-routing) owns `next`: validate once without
writes, read the Board/Wayfinder inventory/current handoffs, return all active TASKs or the first eligible ready
TASK, and stop. Use capability metadata to confirm command availability; do not load every receiving workflow.
Provider `next`/`build` can be explicit-only skills. Local context supplies the historical Board path.

| Evidence-backed state | Next owner/action |
| --- | --- |
| Authorized unimplemented work or confirmed repair | `/skill:build TASK-NNNNN`, preserving prior ledger/cycles; explicit implementation-only request uses Work |
| Committed implementation awaiting acceptance | `/skill:review TASK-NNNNN` in an independent session |
| Accepted behavior without sufficient QA/applicability | `/skill:qa TASK-NNNNN` independently |
| Accepted work with satisfied QA | `/skill:land TASK-NNNNN` within publication authority |
| Initial acceptance missing only explicitly required hosted proof | Authorized draft Land, then focused independent Review |
| Accepted A with administrative B awaiting final delivery checks | Land verifies B and its bridge; no duplicate review merely for metadata |
| Unknown/stale subject, contradictory reports or unavailable prerequisite | Exact evidence owner and reconciliation/recovery action; no inferred pass or speculative repair |
| Land complete | Human review/merge of the actual PR URL |

Use [map-wide planning order](../../planning/CONVENTIONS.md#map-wide-planning-order) for Wayfinder/grill/decomposition.
Finish decisions and approved EPICs, then all accepted TICKET decompositions, then TASK decompositions within the
selected map unless the human explicitly selects a narrower slice. Standalone work and other maps remain independent.
Use actual local IDs, including preserved `T-` requirements; never substitute a provider's example identity.

## State and evidence

[Planning conventions](../../planning/CONVENTIONS.md#readiness-completion-and-review) own status meanings, derived
blocking, legacy authority and automatic parent completion. Scope approval alone is not execution authority.
`ready-for-human` may mean authorization is missing or independent review is next; inspect its actual handoff.
Do not add a separate parent-closeout gate when terminal children already satisfy automatic completion.

Canonical base `.runs/reviews/<TASK-ID>/review.md` and `.runs/qa/<TASK-ID>/qa.md` own independent current verdicts.
Task execution runs own implementation and delivery evidence. Verify subject, snapshot, independence, latest
findings and any proven bridge; status `done`, a tool exit or an old report is not acceptance by itself.
No new tracked planning acceptance narratives are required by these handoffs; do not fabricate receipts or
weaken current evidence validation to make an unavailable report appear durable.

## Delegated requests

Include role, exact target/checkout/base/head/status, owning provider skill, resolved project-context path,
applicable requirements, permitted effects, evidence paths and expected output. Build also carries original
publication authority, pinned models, consumed/remaining repair allowance and liveness ownership. Fresh roles
receive findings and references, not the Engineer conversation or a suggested verdict. They perform one stage
and stop; a child result is evidence to inspect, not permission to expand scope.
