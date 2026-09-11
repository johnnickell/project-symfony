# WF-008 — Complete HTTP Operation Matrix

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-003, WF-005, WF-006, WF-007

## Question

What exact HTTP surface exposes every consumer-relevant released AccessControl workflow while keeping CLI,
worker, and composition-only capabilities deliberately outside HTTP?

## Must decide

- Enumerate authentication, invitation, activation, account, credential, user, role, permission, grant, session,
  managed-policy, and machine-agent operations from the WF-001 classification.
- For every operation, define method and path, API version, request inputs, response output and envelope, success
  and error status codes, authentication and permission, audit outcome, and information-disclosure boundary.
- Define idempotency and concurrency contracts, conditional updates, pagination, filtering, sorting, stable cursors,
  limits, rate/throttle behavior, and asynchronous acceptance where applicable.
- Mark each package capability accounted for as HTTP, CLI, worker, composition-only, or unsupported, with a reason
  and owning ticket for every non-HTTP exclusion.
- Map human operations to SPA journeys while preserving documented API or CLI access for machine and operator
  capabilities that have no required UI.
- Define operation IDs and schema ownership so the OpenAPI artifact is complete and mechanically reviewable.
- Map every row to its Fight Common CQRS dispatch, package command/query or synchronous security service, Action,
  Responder, transaction owner, audit effect, and post-commit delivery/realtime effects.
- Record denied, unauthenticated, invalid, missing, conflicting, expired, revoked, replayed, throttled, and concurrent
  outcomes wherever each can occur; absence from the canonical document must be explicit rather than accidental.

## Required evidence

- A bidirectional inventory covers every externally relevant released command, query, and synchronous service once,
  including invitation delivery/retry/status, email change, password reset, session revocation, managed policy, User,
  Role, Permission, and Agent administration.
- Every client-consumed operation has one stable path/method/operation ID, request and response schema, security
  requirement, success/error status set, Action/Responder pair, CQRS target, audit outcome, and concurrency rule.

## Resolution boundary

This ticket may settle the complete interface matrix. It may not implement routes or schemas, add UI screens, or
expose package internals merely to claim completeness.

## Resolution

Open. Blocked by WF-003, WF-005, WF-006, and WF-007.
