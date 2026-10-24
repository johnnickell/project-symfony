# Execution standards

Implement or revise one accepted, explicitly authorized TASK. Read [project context](PROJECT_CONTEXT.md), the TASK,
its parents, Board and applicable [standards](STANDARDS.md). Independent review owns acceptance; the builder owns
implementation, checks and an honest handoff.

## Place and bound the work

Choose the main checkout or an isolated worktree explicitly unless already selected. Use a feature branch from
`develop`, preferably `feature/task-NNNNN-<slug>`; preserve an existing authorized branch. Never commit directly to
`develop` or `main`. Check ancestry, status and ownership; preserve unrelated files and commits. A commit does not
grant publication, merge, archive, release or deployment authority.

Use the [scratch layout](SCRATCH.md). Resolve the canonical base through Git common-directory/worktree metadata,
not directory names or matching remote URLs. Do not remove another owner's resources or reset uncertain work.

## Intake and implementation

Read the canonical base `.runs/reviews/<TASK-ID>/review.md` and `.runs/qa/<TASK-ID>/qa.md` when present. A missing
review is normal for initial implementation; repairs require current findings matched to subject/base/head/status.
Preserve finding IDs. A newer QA FAIL is repair input despite an older technical accept. INCOMPLETE means recover
missing prerequisites unless a defect is demonstrated. Never edit independent reports.

Implement only accepted behavior. Use clear interfaces, appropriate behavior tests, and regression-first bug
repair. Update directly affected docs; use the installed writing-for-agents skill for instruction changes.
Do not import product policy or package source. Do not invent source edits for an evidence-only finding.

An accepted implementation with applicable QA satisfied routes to [Land](LANDING.md), including mechanical base
reconciliation. Missing explicitly required hosted proof may need authorized draft publication, then focused
independent review; missing optional CI alone is not an implementation defect.

## Verification and handoff

Run focused checks, planning validation when affected, `git diff --check`, and the canonical [build](BUILD.md).
Refresh generated views only when their source edits are authorized. Inspect final/staged diffs and source state.
Report exact commands, exit status, counts, Unit coverage denominator/driver, warnings, omitted checks and risks.
A timeout or inherited receipt is not a fresh pass. Keep secrets out of commands, logs and commits.

Put implementation and verification detail in `.runs/tasks/<TASK-ID>/runs/<run-id>/`; link the exact handoff in
chat and delegated requests. Do not append new execution/acceptance narratives or create planning records merely
to store receipts. Existing tracked requirements remain authoritative until an approved planning-store cutover;
minimal authorized status/navigation corrections remain distinct from the ignored evidence.

Commit only owned changes after the gate. Keep the TASK `ready-for-human` while independent review is pending;
never self-accept or mark done to satisfy publication bookkeeping. A handoff names repository, branch/base/head,
working-tree state, scope, files, evidence, findings, warnings and next owner. Request independent
`/skill:review TASK-NNNNN`, then stop before push/PR unless separately authorized.

Any authorized terminal status update applies [automatic parent completion](../../planning/CONVENTIONS.md#automatic-parent-completion)
in the same operation. This does not grant the implementer independent acceptance or permission to fabricate
verification references. If tracked completion cannot be represented under current evidence rules, retain the
pending state and request the specific planning-owner decision; do not invent a replacement planning system.
