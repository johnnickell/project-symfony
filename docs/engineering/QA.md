# Behavioral QA

QA independently challenges technically reviewed behavior, not just code structure. Read [project context](PROJECT_CONTEXT.md),
the TASK/requirements, current canonical [review](REVIEW.md) and exact source/runtime identity. A material
implementation contributor cannot independently pass their own work.

## Applicability

Changed observable behavior needs proportionate QA before ordinary landing: HTTP, CLI, libraries, workers,
integration, operational tooling and executable instructions all count. No UI does not mean N/A. Pure formatting,
spelling or historical-record changes may have criterion-specific N/A with reasons. Visual N/A is not overall
behavioral N/A. Missing tools/evidence mean INCOMPLETE, not N/A.

Choose intended use plus likely/high-impact rejection, boundary, failure or recovery scenarios from accepted
requirements. Derive expected outcomes independently and check forbidden effects as well as successful results.
Keep scope bounded; do not fabricate endpoints, seed production defects or add tool-testing product fixtures.

## Execution and evidence

Resolve the canonical base through Git metadata. Allocate a unique `.runs/qa/<TASK-ID>/runs/<run-id>/` and publish
an initial INCOMPLETE canonical `.runs/qa/<TASK-ID>/qa.md` before runtime work so interruptions cannot leave an
older PASS appearing current. Use the provider QA report template with the target context; preserve history.

Prove that exercised runtime mounts/images/processes match the reviewed source. Use local wrappers and owned,
isolated services/data. Do not replace another task's stack, use production data, install tools or change host
trust stores implicitly. Disposable local test settings/credentials are permitted within the selected QA scope;
external credentials/effects and publication need separate authority. Keep secrets out of evidence.

Exercise real interfaces with inputs, expected/observed outputs, state/effects, commands and exits. Use safe
ignored probes where needed, not source patches. Instruction walkthroughs are valid labeled evidence for routing,
not claims of live Agent execution. For path changes inspect affected live/archive references and relocation
manifests; planning validation alone does not establish correct destinations.

For UI/TUI changes capture and inspect comparable actual before/after states where required. Record viewport,
interaction, runtime and capture provenance. Images alone do not prove behavior/accessibility; PTY text is not a
screenshot. Missing required captures hold delivery. Do not invent screenshots or claim uninspected images pass.

## Report and handoff

The complete canonical report records exact repository/TASK, requirement revision, branch/base/head/status,
review identity, independence, runtime identity, scenarios, expected/actual results, artifact paths/digests,
findings/dispositions, limitations and publication readiness. Recheck source/runtime at completion and atomically
publish/read back the report. Do not write verdicts or new acceptance narratives into tracked planning.

- PASS: applicable scenarios pass against the current technically accepted subject.
- FAIL: a confirmed defect, with stable ID, criterion, severity, reproduction and forbidden/observed effect.
- INCOMPLETE: required evidence, technical acceptance, identity or runtime prerequisite is missing.
- N/A: scope-grounded non-applicability, not an unexecuted scenario.

FAIL routes to Work, then fresh affected Review/QA. INCOMPLETE routes to the exact prerequisite owner. Satisfied QA
routes to authorized [Land](LANDING.md). Never repair implementation, tests, requirements or independent review
reports; QA does not commit, push, post PR comments, upload artifacts, approve or merge. Retain useful resources
for repair/evidence; cleanup is ownership-proven, never blanket deletion.
