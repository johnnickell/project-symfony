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
- [x] Every configured Fight contract resolves directly from the booted test container; test-only handlers, events,
  providers, and routes remain outside production source.
- [x] The receipt inventories the complete profile, resolved provider versions, reproducible lowest/latest journeys,
  and canonical digests, and Fight Common's exact candidate authority accepts it.
- [x] `./bin/planning-check` and `./bin/build` pass for the expanded profile.

## Verification

Verified 2026-08-31: the isolated lowest Composer resolution booted `PlatformProfile` at the selected candidate;
the latest booted profile and receipt journeys passed (8 tests, 32 assertions); `./bin/planning-check` passed; and
`./bin/build` passed (15 tests, 110 assertions plus production autoload/kernel boot).

Candidate-validation follow-up verified 2026-08-31: the immutable Composer requirement is
`dev-develop#4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16 as 1.2.0-dev`. The repository-owned validator runs ordinary
`composer validate`, permits only Composer's exact commit-reference warning, and rejects validation errors or any
additional warning. `./bin/build` passed with 15 tests and 112 assertions plus production autoload/kernel boot.

PR #6 second-pass architecture repair verified 2026-09-01: moved the public Kernel and controller into
`App\Adapter`, made `public/index.php` explicitly compose the canonical JSON middleware, replaced the catch-all common
service registry with capability-scoped configuration, and removed the artificial project clock/compiler pass and all
event-store wiring. Compiler-pass, serialized Messenger, provider, routing, transaction, container, and HTTP proof now
lives in focused Integration/Functional journeys with fixtures under `tests/Fixture`. The committed lowest and latest
locks remained unchanged; the regenerated receipt records the focused journey paths and no longer claims DBAL event
storage. Focused verification passed with 10 tests and 100 assertions. The detached canonical `./bin/build` exited `0`:
both committed dependency lanes passed without lock drift, the exact candidate authority accepted the receipt,
planning validation passed, the full suite passed with 10 tests and 100 assertions, and the production install/kernel
boot passed. The lowest lane reported 9 upstream PHP 8.5 deprecations without test failures.
