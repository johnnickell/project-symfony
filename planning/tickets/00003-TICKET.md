---
id: T-00003
prd: PRD-00002
title: Adopt Fight Common 1.2
status: ready-for-agent
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

- [ ] The installed candidate is 1.2-compatible under the existing `^1.1` constraint and recorded with its exact reference.
- [ ] Lowest and latest compatible resolutions boot the selected Symfony compiler-pass, Messenger, response, routing,
      transaction, and selected provider journeys.
- [ ] `evidence/framework-support/receipt-v1.json` is canonical, records lock and evidence digests, and passes receipt validation.
- [ ] `./bin/planning-check` and `./bin/build` pass before the receipt is committed.

## Verification

Run the documented lowest/latest Composer and booted journeys, receipt canonicalization, `./bin/planning-check`, and `./bin/build`.
