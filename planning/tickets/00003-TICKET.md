---
id: T-00003
prd: PRD-00002
title: Establish the Symfony Complete Platform Profile
status: done
blocked_by:
---

# Establish the Symfony Complete Platform Profile

## Outcome

Resolve Fight Common through its VCS `dev-develop` candidate, register every documented Symfony and shared-provider
capability with project-owned defaults, and commit this repository's canonical complete-platform receipt.

## Scope

- In scope: complete Symfony compiler-pass/provider composition, documented shared-provider fallbacks, lifecycle evidence,
  and receipt scope.
- Out of scope: copied package source, application-specific secrets/routes/templates/Domain/Application behavior,
  release/publication, and Fight Common 2.0 work.

## Acceptance Criteria

- [x] Composer resolves `johnnickell/fight-common` as `dev-develop` at `4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16` through VCS.
- [x] Every documented Symfony and shared-provider capability is registered with configurable project-owned defaults.
- [x] The receipt inventories the complete profile, resolved provider versions, booted journeys, and canonical digests.
- [x] `./bin/planning-check` and `./bin/build` pass for the expanded profile.

## Verification

Verified 2026-08-31: the isolated lowest Composer resolution booted `PlatformProfile` at the selected candidate;
the latest booted profile and receipt journeys passed (8 tests, 32 assertions); `./bin/planning-check` passed; and
`./bin/build` passed (15 tests, 110 assertions plus production autoload/kernel boot).
