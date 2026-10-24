# Independent review

Review is read-only over implementation and planning. Read the target [project context](PROJECT_CONTEXT.md),
accepted requirements, [standards](STANDARDS.md), actual diff and verification. A material contributor to the
implementation or submitted evidence cannot independently accept it. One independent reviewer may perform both
named passes; builder self-checks are not acceptance.

## Two passes

| Spec ID | Challenge |
| --- | --- |
| SP-01 | Every accepted outcome delivered within scope |
| SP-02 | Validation, rejection, permission and ownership on every applicable entry path |
| SP-03 | Commands, queries, events, mutations and effects match the contract |
| SP-04 | Failure, compatibility, recovery and secret-safety obligations hold |
| SP-05 | Meaningful tests/direct checks and fresh evidence establish the claimed outcomes |

| Standards ID | Challenge |
| --- | --- |
| ST-01 | Package ownership, inward dependencies, orchestration and injection |
| ST-02 | Transport scope, safe representations and authoritative policy |
| ST-03 | Naming, PHP, documentation and local conventions |
| ST-04 | Meaningful behavioral coverage and proportionate integration without testing tools |
| ST-05 | Planning, delivery, verification, resource ownership and truthful warnings |

Record Pass/Fail/Unverified/N/A with exact evidence for every ID; justify N/A. Map every TASK criterion to the
applicable IDs. Inspect actual callers and tests, including independent expectations, plausible defects caught,
legitimate interaction assertions and replacement coverage. A green build cannot prove semantic correctness.
Separate non-blocking taste from demonstrated acceptance, correctness, security, scope or evidence defects.

For moved documents/evidence, inspect all affected live/archive records, templates, Markdown links/anchors,
code-span and plain-text paths against intended targets and the relocation manifest. Preserve historical paths
as history with a current locator; a passing planning validator alone is insufficient.

## Evidence and disposition

Identify repository, TASK, branch, full base/head and working-tree state. For dirty reviews, retain file digests or
a patch for every reviewed version. An unchanged patch against a changed base still needs interaction assessment.
Run fresh focused checks and the [canonical gate](BUILD.md) when required; distinguish inherited receipts from
new results. Disclose warnings, skips, unavailable checks and gaps. Hosted evidence is required when accepted
local policy/TASK or actual delivery restrictions require it, not merely because a workflow file exists.

Give actionable findings stable IDs, severity (critical/high/medium/low), affected pass/criterion, reproduction,
expected/observed behavior, evidence and correction. `accept` requires every applicable ID Pass, sufficient proof
for every criterion and no blockers. Any Fail or required Unverified yields `revise`.

If only explicitly required hosted acceptance proof is absent, preserve revise/Unverified and route to authorized
draft publication, not speculative source repair. Independently reassess the evidence on return. A current QA
FAIL cannot be ignored in favor of older acceptance. Assess [QA applicability](QA.md#applicability) after acceptance;
non-UI behavior and executable instructions can require QA.

## Canonical report

Resolve the canonical base with Git metadata. Publish only to `.runs/reviews/<TASK-ID>/review.md` there, preserving
older history and replacing the canonical file atomically only after its complete replacement is ready. Read it
back. A failed write is an incomplete handoff, not an actionable chat-only verdict. Do not append acceptance
narratives to tracked planning.

Use the provider's version-3 TASK report contract:

```yaml
---
review_handoff_version: 3
task: TASK-NNNNN
target_branch: feature/task-NNNNN-slug
base_commit: <full OID>
head_commit: <full OID>
target_status: clean
verdict: accept
---
```

For a dirty subject, replace `clean` with its full porcelain status (a YAML block scalar when needed), plus digests
or retained patch in the body. Include independence, separate Spec/Standards tables, criterion mapping, findings,
commands/results, limitations, risks, QA applicability and next owner. Matching version-2 reports remain usable;
never choose a historical PASS over the canonical current report. Preserve report bytes during relocation.

[Land](LANDING.md) may prove a mechanical reconciliation bridge without rewriting the independent report to claim
new OIDs were reviewed. Requirements, instructions, dependencies and behavior changes need fresh applicable
review; an administrative commit alone does not invalidate accepted implementation. Review never fixes source,
finalizes planning, pushes, approves or merges.
