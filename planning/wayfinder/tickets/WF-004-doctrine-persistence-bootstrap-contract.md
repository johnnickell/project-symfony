# WF-004 — Doctrine Persistence and Bootstrap Contract

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-001, WF-002

## Question

How should this project own Doctrine persistence and initial administration while preserving package semantics,
transactional integrity, concurrency safety, and repeatable local setup?

## Must decide

- Define project-owned Doctrine mappings, records, repositories, type conversions, indexes, and migration
  authority for every released persistence port the starter composes.
- Define transaction boundaries, compare-and-save or optimistic/pessimistic concurrency behavior, unique-conflict
  handling, rollback guarantees, and audit atomicity.
- Define managed-policy reconciliation ownership, convergence, conflict behavior, safe deletion boundaries, and
  its relationship to authored roles, permissions, and grants.
- Define schema creation, migration ordering, clean-clone database bootstrap, test database isolation, fixtures,
  and recovery behavior for interrupted or failed migrations.
- Define an idempotent CLI administrator bootstrap that is invitation-led, auditable, non-interactive when
  explicitly configured, safe to retry, and never exposed as a public first-user route.

## Resolution boundary

This ticket may use disposable persistence prototypes against the WF-002 local topology. It may not add mappings,
migrations, repositories, tables, bootstrap commands, or seed data, and it may not reproduce package-owned Domain
or Application logic.

## Resolution

Open. Blocked by WF-001 and WF-002.
