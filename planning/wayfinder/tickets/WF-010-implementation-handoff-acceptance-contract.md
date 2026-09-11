# WF-010 — Implementation Handoff Acceptance Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** Human approval of the handoff
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-002, WF-003, WF-004, WF-005, WF-006, WF-007, WF-008, WF-009

## Question

What evidence must the eventual implementation handoff require so a clean clone is a complete, secure, documented,
and independently verifiable local-development starter rather than a collection of unproved components?

## Must decide

- Define vertical implementation slices, epic/PRD/ticket ownership, dependency ordering, acceptance demonstrations,
  and explicit exclusions without turning this Wayfinder record into executable work.
- Define exact production-code Unit coverage, meaningful Integration boundaries, limited high-value Functional and
  browser journeys, concurrency probes, negative security cases, and the boundary between tests and tooling checks.
- Define static analysis, architecture, formatting, migration, frontend, OpenAPI, dependency, secret-scan, and
  vulnerability gates, plus how warnings, skips, and unavailable infrastructure are reported.
- Define clean-clone setup, deterministic Compose boot, migration, administrator bootstrap, worker/scheduler/hub
  health, build, teardown, persistence, worktree isolation, and human UAT evidence.
- Define complete documentation for environment variables, local URLs, credentials/bootstrap, API/OpenAPI,
  workers, schedules, failures, realtime subscriptions, SPA development, troubleshooting, and safe cleanup.
- Define security acceptance for tokens, cookies, origin/CSRF, authorization, enumeration, throttling, secrets,
  audit, queue replay, Mercure subscriptions, and dependency boundaries.
- Require one valid checked-in OpenAPI document and rendered local Swagger UI, normalized downstream parity input,
  generated-client compilation, unauthorized-operation and private-subscription rejection, JWT rotation/reuse,
  invitation-led bootstrap, queue retry/outbox, scheduler overlap, and failure-recovery journeys.
- Require the immutable Symfony client source reference and copy manifest used by Laravel, Slim, CodeIgniter, and Yii,
  while keeping their backend implementations and local generated bindings independently owned.
- Define the repository-owned `./bin/build` acceptance role while keeping release, publication, deployment, and
  production-readiness qualification separate.

## Resolution boundary

This ticket may settle the handoff and evidence contract and link the resulting epic, PRDs, and implementation
tickets. It may not implement, commit generated runtime evidence, publish, release, deploy, or archive this map as
a completion side effect.

## Resolution

Open. Blocked by WF-002 through WF-009.
