---
id: T-00005
prd: PRD-00002
title: Establish the Canonical Symfony Pre-Submit Quality Gate
status: done
blocked_by:
---

# Establish the Canonical Symfony Pre-Submit Quality Gate

## Outcome

Make `./bin/build` the permanent clean-clone completion gate for the Symfony starter while preserving its
framework-native boot, dependency-lane, receipt, production-install, and planning evidence, and align its minimal
FPM runtime with the proven Fight CMS and Omphalos container pattern.

## Portfolio Provenance

- Fight Common [T-00087](https://github.com/johnnickell/fight-common/blob/develop/planning/tickets/00087-TICKET.md)
- Fight Common PRD-00018

## Scope

- In scope: ordinary Composer validation with only the temporary candidate-reference warning allowed; repository
  PHPCS/fixer check; PHPStan; Deptrac; Rector dry-run; PHPUnit with exact 100% production statement coverage;
  existing lowest/latest dependency lanes; booted Symfony HTTP, console, and production-container journeys;
  receipt generation and independent authority validation; clean `--no-dev` installation; and hosted CI delegation
  to `./bin/build`; host-boundary planning validation; and the existing two-service Compose runtime using the
  established non-root FPM user, bounded pool configuration, and FIFO stdout workaround.
- Out of scope: copying Fight package source, publishing aggregate production profiles or receipt authorities,
  weakening existing checks, implementing new business capabilities, release publication, or centralizing this
  repository's gate in Fight Common.

## Acceptance Criteria

- [x] A clean clone can run only `./bin/build` and receive the complete ordered local verdict.
- [x] PHPCS/fixer, PHPStan, Deptrac, Rector dry-run, and PHPUnit are locked development dependencies and execute
      inside the repository build image without baselines or suppressed failures.
- [x] Deptrac enforces Adapter to Application to Domain, rejects unclassified production code, and keeps Symfony
      types at Adapter and composition boundaries.
- [x] Direct unit tests mirror owned production classes; Integration and Functional journeys remain separate;
      exact statement coverage applies to all owned production code and rejects coverage-ignore directives.
- [x] Existing Composer candidate validation, lowest/latest resolution, support-receipt authority, framework boot,
      planning, documentation, and clean production-autoload checks remain in the canonical gate.
- [x] `./bin/build` invokes planning validation once at the host boundary, where `git check-ignore` works for normal
      and linked worktrees, without adding Git-metadata mounts solely for that assertion.
- [x] The FPM Dockerfile and Compose configuration follow the applicable Fight CMS and Omphalos runtime learnings:
      non-root execution, bounded `ondemand` pool settings, clean FIFO stdout streaming, and only the Symfony
      starter's required `api` and `server` services.
- [x] Capability probes and fixtures remain test-only; no synthetic Domain events, global platform profile, or
      receipt-authority service is added to production merely for test convenience.
- [x] `.github/workflows/build.yml` invokes `./bin/build` and does not duplicate its ordered checks.
- [x] Local and exact-head hosted results are recorded separately.

## Verification

- Focused tests for the gate scripts, Deptrac classification, coverage policy, and workflow delegation.
- `./bin/planning-check`
- `./bin/build`
- Exact-head hosted build after publication.

## Completion Notes

The dirty implementation was reconciled onto recertified `develop` merge
`1b6db511ebe39177be6195b6ad491534bd55f0ab` without changing the selected Fight Common source tree. The latest
and lowest locks resolve rewritten candidate `fad24ae9fdcf4ac00fa55c59ef7d35f7c7531911`; their digests and the
support receipt were regenerated from the reconciled manifest.

Local `./bin/build` completed with exit `0`: both dependency lanes booted, receipt authority passed, PHPCS,
PHPStan, Deptrac, and Rector passed, Deptrac reported zero uncovered or unassigned tokens, PHPUnit passed with 22
tests and 182 assertions, statement coverage was exact at 41/41, and the production `--no-dev` kernel boot passed.
The rebuilt Compose runtime returned HTTP 200 and ran the FPM master and worker as UID 1000. The exact-head hosted
build is a separate post-push merge gate: its result is recorded on the pull request, and the branch may not merge
unless that check passes against the pushed commit.
