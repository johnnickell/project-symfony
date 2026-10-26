# Landing

Publish one independently accepted TASK and return merge control to the human. Read [project context](PROJECT_CONTEXT.md),
current requirements, Board, canonical [Review](REVIEW.md) and [QA](QA.md), branch/base/head, remote PR state and
resource ownership. Explicit Land or delegated Build authority permits bounded non-force publication and owned
cleanup, not implementation repair, force push, approval, merge, release, deployment or planning archive.

## Acceptance and reconciliation

Ordinary landing requires independent acceptance, applicable QA PASS or justified N/A, and required verification.
A newer QA FAIL or unresolved identity defeats an older PASS. Missing optional CI is disclosed, not invented as
an acceptance prerequisite. Preserve explicit local/TASK hosted requirements and actual hosting restrictions.

An advancing base can be reconciled mechanically: record old/new OIDs, inspect range-diff and interacting base
changes, regenerate authorized planning views, and run fresh required checks. Rebase only an unpublished branch;
never rewrite published history. Record the provenance bridge in ignored task evidence, not by rewriting the
independent report. Changed requirements, instructions, dependencies, behavior or uncertain integration need
fresh independent review; Land cannot repair implementation defects.

## Candidate and administrative closeout

Acceptance belongs to exact implementation candidate A. A later administrative B may contain only truthful
completion/PR metadata and generated views, not requirements or implementation changes. Under this convention,
acceptance of A and delivery of B remain distinct; B needs applicable final-head delivery proof before successful
landing/cleanup. A pre-existing literal-final-head acceptance criterion cannot be silently reinterpreted: obtain
its owner's amendment or retain pending status. Do not loop through status commits to manufacture acceptance.

Keep full acceptance, bridge and delivery evidence in canonical ignored reports and task runs; no new tracked
acceptance narratives. Minimal authorized status changes obey current planning validation. Where evidence metadata is needed, use an
existing durable reference or an authorized HTTPS report location; never make a clean clone depend on a link to
an ignored local file. Terminal updates roll up eligible parents automatically. If required tracked evidence fields cannot be satisfied without prohibited
new narratives or unavailable references, keep status pending and request the specific owner decision. This is
not authorization to remove validators or migrate planning storage.

## Required hosted evidence before acceptance

When a current independent report confirms that only explicitly required hosted acceptance proof is missing,
use authorized draft publication of that exact candidate. Preserve revise/Unverified and incomplete TASK state.
Record actual run URL, event, source head/base, tested commit and job results. Distinguish merge-candidate from
literal source-head evidence. Route returned evidence to focused independent Review, not an empty repair.
Do not make a tracked PR-metadata commit merely to move a candidate still awaiting proof.

## Verify, publish and clean

Run focused checks, planning validation, diff check and the canonical [build](BUILD.md) on the final owned tree
before committing/publishing. Refresh views only for authorized metadata changes. Stage explicit owned paths.
Use the local [PR template](../../.github/pull_request_template.md) and verify title `TASK-NNNNN — <TASK title>`,
the bound target base, correct head branch and exact remote OID. Before reconciliation, pre-publication and
final handoff resolve the live GitHub default against Symfony's `develop` policy and existing subject PR using
the resolved provider's `.pi/skills/land/scripts/bind-target.py` with target `--root`, `--policy develop` and
existing `--pr`/`--head`. Retain its observation and use the bound base throughout. Stale `origin/HEAD` is not
authority; default/policy/PR conflicts require explicit owner disposition, not silent retargeting. Reuse the existing TASK PR; never create duplicates after
an uncertain request. Required final-head checks cannot reuse A's run for B. Preserve draft status/resources
while proof is missing. Publication receipt and PR body record delivery without another tracked evidence commit.

Publish sanitized required captures only within publication authority using available, verified upload support.
Inspect partial results before retrying, and confirm audience access. Local paths are not published PR evidence.
An unavailable capture/upload path is a named delivery prerequisite, not fabricated success.

Publication, current authored/generated handoff synchronization and eligible local resource retirement are
separate required obligations. Apply the resolved provider's `docs/engineering/LANDING.md` cleanup-only intake,
inventory/preservation, live-consumer, Git/Docker retirement and postcondition/resume sections (TASK-00534 plus
qualified TASK-00537) with Symfony's gates. Remove eligible clean inactive isolated worktrees/local refs only
with exact publication/merge proof and zero unpublished commits, from a safe checkout, without force. Removal
is not the final tool operation: verify filesystem/registration/ref absence and retained data/evidence/runtime.

Verified merged cleanup-only needs no metadata correction PR or repeated acceptance gate. Discover orphan stacks
independently of registered worktrees, corroborating paths/source/topology/data ownership rather than names.
After the worker exits continue from the safe checkout with original model/ledger/authority, preserving active
coordinators. Positively qualify Docker VM read-only sharing using all containers (including stopped), parent
mounts, host cwd/writers and stable preservation manifests. Unknown readers hold; never shut down the shared VM.
Preserve active/dirty/shared resources, independent reports, credentials and durable data. Resume only missing
effects; a held resource means incomplete cleanup with an exact finite recovery action, not successful Land.
Never archive planning records as a completion side effect. End with actual delivery state and distinct cleanup
results; human merge is separate.
