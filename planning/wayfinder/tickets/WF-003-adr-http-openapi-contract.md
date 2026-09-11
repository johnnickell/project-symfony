# WF-003 — ADR HTTP and OpenAPI Contract

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-001

## Question

Which Symfony-native request/response composition proves a consistent ADR API while preserving the released
package contracts and making OpenAPI documentation authoritative?

## Must decide

- Prototype invokable Action services, Domain/application delegation, and Responder ownership without creating a
  Fight bundle or moving framework types across the adapter boundary.
- Define request DTO construction, validation, normalization, unknown-field handling, content negotiation, and
  stable response-envelope rules.
- Define API versioning, route naming, status-code semantics, exception-to-problem mapping, correlation metadata,
  and safe error disclosure.
- Select the OpenAPI authority and generation/verification workflow so documented operations and schemas cannot
  silently drift from executable HTTP behavior.
- Generate exactly one OpenAPI 3.1 document in one pass from the installed Fight AccessControl
  `resources/openapi/` schema carriers plus Symfony-owned Actions, request/response DTOs, routes, security
  declarations, and project components; never generate and merge separate specs.
- Make Symfony authoritative for canonical paths, methods, operation IDs, payload and safe-error semantics,
  authentication behavior, and client-consumed realtime event shapes while leaving servers and generated ordering
  free to vary in downstream starters.
- Keep the generator command, checked-in artifact, Swagger UI, servers, tags, operations, security schemes, status
  codes, framework errors, and drift check Symfony-owned.
- Establish how authentication, authorization, pagination, idempotency, and asynchronous acceptance are expressed
  consistently before enumerating operations in WF-008.

## Resolution boundary

This ticket may use disposable prototypes to settle HTTP architecture and documentation authority. It may not
implement production endpoints, choose persistence details, or claim that an operation exists before WF-001 finds
its released package contract and WF-008 assigns its complete interface.

## Resolution

Open. Blocked by WF-001.
