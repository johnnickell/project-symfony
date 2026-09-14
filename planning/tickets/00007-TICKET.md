---
id: T-00007
prd: PRD-00002
title: Establish the Lean Symfony Pre-Submit Quality Gate
status: done
blocked_by:
---

# Establish the Lean Symfony Pre-Submit Quality Gate

## Outcome

Create the Symfony-owned lean `./bin/build` pre-submit gate required by Fight Common
[T-00087](https://github.com/johnnickell/fight-common/blob/develop/planning/tickets/00087-TICKET.md) and
[ADR 0026](https://github.com/johnnickell/fight-common/blob/develop/planning/adr/0026-lean-pre-submit-and-release-qualification.md).

## Scope

- Require `johnnickell/fight-common:^1.2`, the installed `FightCommon` PHPCS standard, and repository-owned scan
  paths/exclusions.
- Make `./bin/build` the sole local and hosted pre-submit gate. It runs every retained Unit, Integration,
  Functional, frontend, and browser suite once, plus Composer validation, syntax/formatting, PHPCS, PHPStan,
  Deptrac, and Rector dry-run.
- Direct Unit tests use `#[CoversClass]` and alone prove exact 100% owned-production statement coverage;
  retained framework boundaries and valuable application journeys use `#[CoversNothing]`.
- Retain Symfony-native boundary coverage and valuable journeys while framework types remain in Adapter and
  composition code under Adapter -> Application -> Domain.

## Exclusions and Cleanup

- Remove `tests/Tooling`, certification lanes, candidate validation, lowest/latest lanes, receipts and receipt
  authorities, auxiliary locks/digests, clean production-install inspection, and Fight Common certification
  journeys from ordinary builds.
- Do not test build scripts, CI, configuration, coverage tooling, receipts, certification-only fixtures, or docs.
  Hosted CI calls `./bin/build` only; hosted status remains separate delivery evidence.

## Acceptance Criteria

- [x] One canonical gate executes each retained suite once and every retained quality check.
- [x] The installed package/standard, local paths/exclusions, and direct Unit-only exact coverage are enforced.
- [x] `#[CoversClass]` and `#[CoversNothing]` metadata preserve direct coverage ownership without masking gaps.
- [x] Symfony-native boundaries and valuable application journeys remain; Tooling and certification lanes are
      absent from ordinary builds.

## Verification

- Run focused retained checks and `./bin/build` during the implementation ticket.
- Confirm CI delegates only to `./bin/build`; record its status separately.

## Completion

Completed 2026-09-13. `./bin/build` now performs one Docker-backed planning, Composer, syntax, static-analysis,
formatting, exact Unit-coverage, Integration, and Functional pass. Fight Common resolves through the released 1.2
constraint, the project composes its installed PHPCS ruleset, and the former candidate, receipt, lowest-lock,
production-install, and Tooling-test certification machinery is removed.
