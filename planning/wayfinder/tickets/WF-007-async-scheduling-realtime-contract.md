# WF-007 — Async, Scheduling, and Realtime Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-002, WF-004, WF-005, WF-006

## Question

What reliable local contract should connect committed AccessControl work to Redis-backed workers, scheduled tasks,
asynchronous delivery, and authorized Mercure SSE updates?

## Must decide

- Define Messenger buses, Redis stream transports, routing, serialization/versioning, unique consumer names,
  concurrency, acknowledgement, retry/backoff, dead-letter or failure transport, redelivery, and operator commands.
- Define transaction-to-dispatch ordering and whether any durable handoff is required so rolled-back work is never
  delivered and failed publication remains recoverable without overstating broker guarantees.
- Define Scheduler providers, cadence and timezone authority, overlap prevention, missed-run behavior, worker
  consumption, graceful shutdown, and observable failure semantics.
- Classify mail and other external delivery as synchronous or asynchronous, with retry safety, idempotency,
  correlation, and testing seams.
- Define Mercure hub composition, versioned allowlisted event envelopes, topic naming, private updates,
  subscription authorization, reconnection, last-event handling, and authoritative refetch behavior.
- Carry only minimal invalidation data on authorized private topics; reject unauthorized subscriptions and require
  every client signal to refetch authoritative API state.
- Define which released package events are externally observable, internal-only, coalesced, or deliberately absent
  from realtime delivery.

## Resolution boundary

This ticket settles async, schedule, and realtime contracts for local development. It may not configure transports,
run workers, create schedules, publish events, or make production durability or availability claims.

## Resolution

Open. Blocked by WF-002, WF-004, WF-005, and WF-006 so realtime cannot precede settled authentication and
authorization.
