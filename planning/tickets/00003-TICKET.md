---
id: T-00003
prd: PRD-00002
title: Adopt Fight Common 1.2
status: done
blocked_by:
---

# Adopt Fight Common 1.2

## Outcome

Resolve a Fight Common 1.2 candidate through Symfony's own Composer installation, activate only locally supported
capabilities, run lowest/latest booted journeys, and commit this repository's canonical support receipt.

## Scope

- In scope: lockfile upgrade, selected Symfony compiler-pass/provider composition, lifecycle evidence, and receipt.
- Out of scope: copied package source, unselected optional dependencies, release/publication, and Fight Common 2.0 work.

## Acceptance Criteria

- [x] The installed candidate is 1.2-compatible under the existing `^1.1` constraint and recorded with its exact reference.
- [x] Lowest and latest compatible resolutions boot the selected Symfony compiler-pass, Messenger, response, routing,
      transaction, and selected provider journeys.
- [x] `evidence/framework-support/receipt-v1.json` is canonical, records lock and evidence digests, and passes receipt validation.
- [x] `./bin/planning-check` and `./bin/build` pass before the receipt is committed.

## Verification

Verified 2026-08-31: isolated lowest resolution, latest booted journeys (6 tests, 23 assertions), receipt
canonicalization, `./bin/planning-check`, and `./bin/build` (13 tests, 98 assertions plus production autoload/kernel boot).
