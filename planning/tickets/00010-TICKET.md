---
id: T-00010
epic: EPIC-00001
title: Establish Reusable Local and Hosted Verification
status: needs-info
blocked_by: T-00009
approved: yes
---

# Establish Reusable Local and Hosted Verification

## Outcome

Provide one repeatable, noninteractive local and hosted quality verdict from a prepared running container, without
rebuilding images or installing dependencies on every invocation and without losing Unit-only coverage evidence.
A clean checkout still has a documented, verified path to that verdict.

## Approval and execution boundary

John approved this requirement area under [EPIC-00001](../epics/00001-EPIC.md). This is a requirement TICKET,
not an implementation TASK. Planning cutover is complete; `needs-info` records the missing accepted TASK handoff
and unfinished predecessor outcomes, not an outstanding adoption approval. [T-00009](00009-TICKET.md), following
T-00008, supplies the accepted engineering/review guidance before implementation. No TASK or new PRD is created here.

## Scope

### Included use cases

- A developer starts from a clean checkout and follows explicit repository-owned runtime/dependency preparation,
  obtaining the PHP capabilities, locked dependencies, and coverage driver required by the gate.
- A developer reruns `./bin/build` against the prepared running Compose PHP service. A thin wrapper invokes named,
  fail-fast PHP phases without an implicit image rebuild, dependency install/update, or parallel quality pipeline.
- A developer invokes the gate without preparation and receives a clear nonzero failure and actionable next step,
  rather than an apparent pass or hidden runtime/dependency mutation.
- A reviewer obtains the complete suite/check results and an independently attributable Unit coverage report that
  remains intact after Integration and Functional execution.
- Hosted CI performs explicit preparation and delegates the same verification to `./bin/build`, preserving the
  clean-clone contract and reporting an exact-head result separately from local verification.

### Affected boundaries and documentation

Repository build/preparation wrappers, PHP phase orchestration, the required Compose PHP execution environment,
coverage-report configuration, hosted setup, and contributor quick-start/verification documentation are in scope.
Retain all checks and valuable suites established by [T-00007](00007-TICKET.md), including planning and Composer
validation, PHP syntax, installed-standard PHPCS, PHPStan, Deptrac, Rector dry-run, exact Unit coverage, Integration,
and Functional tests. Changes to test classification belong to [T-00012](00012-TICKET.md), not this gate migration.

Existing planning validation may remain Python-owned; do not duplicate it in PHP or add Python gate orchestration.
Choose the host/container boundary for planning validation so normal and linked worktrees remain usable without
unsafe Git-metadata sharing. Shared caches/resources must not compromise worktree isolation.

### Exclusions

Dependency upgrades, changed package APIs, new application services, persistence/worker/browser runtime expansion,
full WF-002 topology, frontend/browser suites without an authorized feature, quality-tool self-tests, invalid
source fixtures, revived candidate-certification/receipt lanes, and reduced checks or broader coverage exclusions.
This ticket does not authorize publication; hosted evidence is obtained through separately authorized delivery.

## Acceptance Criteria

- [ ] AC-01 — A documented clean-clone preparation path supplies the required locked dependencies, PHP extensions,
      coverage driver, and running service without depending on host PHP or a sibling checkout's vendor directory.
- [ ] AC-02 — `./bin/build` delegates to named, fail-fast PHP orchestration in the already-running repository-owned
      Compose service. It neither rebuilds images nor installs/updates dependencies as ordinary pre-submit work.
- [ ] AC-03 — Missing preparation and genuine phase failures return nonzero with the failing prerequisite/phase;
      later phases do not disguise failure. The gate and hosted preparation remain noninteractive.
- [ ] AC-04 — Every currently required quality phase and retained suite executes; each retained suite runs once.
      Focused wrappers remain iteration tools, not additional canonical test passes.
- [ ] AC-05 — Exact Unit-only owned-production statement coverage remains mandatory. The denominator, coverage
      metadata, existing exclusions, ignore-directive rejection, and report validity checks are preserved. Combined
      coverage cannot substitute for Unit evidence, and missing or unavailable coverage cannot count as success.
- [ ] AC-06 — A distinct Unit artifact survives the whole build. A unified PHPUnit invocation is used only if it
      independently proves Unit-only coverage; otherwise separate non-duplicated suite execution is retained.
- [ ] AC-07 — Hosted CI prepares the environment explicitly and invokes the same canonical gate without duplicating
      checks. Fresh clean-clone local and exact-head hosted results are recorded separately with warnings and limits.
- [ ] AC-08 — Preparation, focused commands, canonical verification, caches/artifacts, failure diagnosis, and the
      replacement of T-00007's setup mechanics are documented. Normal and linked-worktree execution preserve isolation.
- [ ] AC-09 — Independent review confirms no test/tooling self-tests, dependency upgrades, weakened checks, runtime
      expansion, or historical certification claims were introduced by the gate migration.

## Verification

During later authorized implementation:

- Run the real documented preparation and `./bin/build` from a clean checkout, then rerun the gate without changing
  the prepared environment. Record phase/suite counts, exits, warnings, and evidence that preparation is not repeated.
- Exercise an actually unprepared isolated environment to prove the actionable failure. Inspect phase failure
  propagation directly; do not seed invalid code, fabricate coverage reports, or create product tests of the gate.
- Inspect the final Unit artifact after all suites, reporting covered/total statements, driver, source scope, and
  exclusions. Trace that exact artifact to the coverage decision rather than reporting a combined percentage.
- Verify normal and linked-worktree behavior with owned resources and no interference with unrelated work.
- Run `./bin/planning-check` and `git diff --check`. Follow the repository's detached-build procedure for a long gate
  and require an exit file containing `0`; a foreground timeout is not verification.
- Obtain independent Spec and Standards review and, after separately authorized publication, the exact-head hosted
  result. Do not describe local success or historical CI as hosted proof.

## Applicability

No business command, query, event, permission, or request-validation contract is introduced. Validation concerns
real preparation, phase results, coverage evidence, and planning metadata. Security concerns include secret-safe
logs, resource ownership, worktree isolation, and unchanged package provenance. Composer installation uses the
accepted lock without upgrading packages; no production HTTP, persistence, or security adapter behavior changes.

## Implementation TASKs

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:children -->

## Completion Notes

Requirements approved and recorded only. Planning migration is complete; TASK handoff and predecessor outcomes
remain outstanding.
No preparation, gate implementation, local full-build result, or hosted acceptance is claimed here.
