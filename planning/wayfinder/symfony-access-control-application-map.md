# Symfony AccessControl Starter Application

**Label:** `wayfinder:map`
**Status:** Active

> This map is an **index, not a store**. Each material decision lives in exactly one linked ticket under
> `tickets/`; this map only summarizes the linked resolutions and shows the next decision frontier.

## Destination

Chart an implementation-ready, local-development Symfony starter consuming released Fight package contracts.
Common v1.4.0 and AccessControl v0.8.0 are the installed baseline selected by the latest-stable upgrade request.
AccessControl v0.4.0 remains the last full audit baseline; WF-001 must verify the complete released delta and
unresolved schema findings before downstream application decisions proceed. The planned
application composes MySQL, Redis, Nginx, PHP-FPM, PHP-CLI workers, Cron scheduling, private Mercure SSE, every
consumer-relevant AccessControl workflow, OpenAPI documentation, and an editable React SPA under `client/`
compiled into `public/dist/`.

The starter owns Symfony composition and every framework, persistence, HTTP, security, and presentation adapter.
It consumes the packages through their public Composer contracts only; it does not copy package Domain or
Application source or introduce a Fight bundle.

**Done** = every linked decision ticket is closed, the operation matrix accounts for every consumer-relevant
released AccessControl capability or explicitly excludes it as non-HTTP, all remaining fog is resolved or
excluded, and the map links to its resulting accepted EPIC, requirement TICKETs, and approved TASK handoff under
the verified [local lifecycle](../CONVENTIONS.md). Existing legacy PRD/ticket references retain their authority;
this routing clarification changes no WF decision or implementation gate.

## Notes

- This is a decision-only map. It introduces no application API, database schema, environment variable, package
  dependency, runtime behavior, or release claim.
- The Compose topology is certified for local development only. Deployment and production topology are separate.
- Initial administration is invitation-led, with an idempotent CLI bootstrap and no public first-user or
  self-registration backdoor.
- Mercure is the selected realtime transport. The SPA is editable starter source, not a shared runtime package.
- Fight AccessControl v0.4.0 ships scan-only reusable schemas under `openapi/`, loaded through
  `openapi/bootstrap.php`; the earlier `resources/openapi/` assumption is superseded by release evidence.
  Symfony owns canonical paths, methods, operation IDs, wire semantics, authentication behavior, the realtime
  event contract, and the complete document. The catalog mismatch recorded in WF-001 must be resolved.
- Symfony generates one OpenAPI 3.1 document in one pass by scanning the installed package schema resources and its
  own Actions, DTOs, routes, security declarations, and project-specific components. It never merges two specs.
- The handwritten client follows the public Fight CMS React/TypeScript/ESBuild/Sass structural baseline, becomes
  the immutable source-copy reference for downstream starters, and generates bindings from Symfony's local spec.
- Machine and operator capabilities must remain fully usable through the documented API or CLI without mandatory
  UI coverage.
- Private reference lessons may be translated into generic requirements, but private identities, paths, hashes,
  internals, and derivation claims must not enter public artifacts.
- Current Symfony documentation makes the proposed composition feasible: controller services with `handle()`
  support the selected ADR shape; Responders use `respond()`; Scheduler schedules are consumed by Messenger workers; Redis Messenger transport uses
  streams and requires unique consumer names; and Mercure provides an SSE hub. See
  [controller services](https://symfony.com/doc/7.4/controller/service.html),
  [Scheduler](https://symfony.com/doc/current/scheduler.html),
  [Messenger](https://symfony.com/doc/current/messenger.html), and
  [Mercure](https://symfony.com/doc/current/mercure.html).
- WF-001 preserves [v0.1.0 baseline evidence](research/WF-001-released-package-contract-audit-research.md) and now
  records the [v0.4.0 released-contract audit](research/WF-001-v0.4.0-contract-delta-research.md). John replaced the
  earlier v0.2.0 target with v0.4.0. Release availability and declared Common compatibility are verified, but the
  invitation-status schema contradicts the released view and delivery catalog coverage has gaps. John's earlier
  v0.5.0 correction plan is historical. The current released v0.8.0 baseline needs full delta and schema/regression
  verification; installation is not accepted audit evidence.
  [WF-001](tickets/WF-001-released-package-contract-audit.md#upstream-resolution-plan-and-resume-condition) owns
  the resume conditions. Do not create duplicate upstream work.

## Decisions so far

1. **[Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) is open.** The v0.4.0
   audit establishes release identity, published installable metadata, declared Common compatibility, schema
   resources, and the public-contract delta. The selected v0.8.0 baseline removes the release wait; closure
   still requires schema/regression verification and a full delta audit including Agent workflows.
2. **[Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) is closed.**
   Accepted isolated HTTPS origins, service topology and image policy, explicit readiness, and data-preserving
   shutdown. Runtime implementation and qualification remain later work.
3. **[ADR HTTP and OpenAPI Contract](tickets/WF-003-adr-http-openapi-contract.md) is open.** Set the request,
   response, validation, error, versioning, and documentation authority.
4. **[Doctrine Persistence and Bootstrap Contract](tickets/WF-004-doctrine-persistence-bootstrap-contract.md) is
   open.** Set adapter-owned storage, transaction, reconciliation, migration, and initial-administrator behavior.
5. **[Authentication and Account Security Contract](tickets/WF-005-authentication-account-security-contract.md) is
   open.** Set Symfony Security, JWT, refresh, browser-defense, and account-lifecycle behavior.
6. **[Principal and Authorization Mapping](tickets/WF-006-principal-authorization-mapping.md) is open.** Map package
   authorization to authenticated human and machine principals.
7. **[Async, Scheduling, and Realtime Contract](tickets/WF-007-async-scheduling-realtime-contract.md) is open.** Set
   queue, failure, schedule, delivery, Mercure topic, and subscription behavior.
8. **[Complete HTTP Operation Matrix](tickets/WF-008-complete-http-operation-matrix.md) is open.** Account for every
   consumer-relevant HTTP operation and every deliberate non-HTTP exclusion.
9. **[React SPA Architecture and Journeys](tickets/WF-009-react-spa-architecture-journeys.md) is open.** Set the
   editable frontend structure, token lifecycle, routing, white-label visual system, and human workflows. Backend
   decisions remain in WF-002 through WF-008 in this map; WF-009 consumes their public client contract.
10. **[Implementation Handoff Acceptance Contract](tickets/WF-010-implementation-handoff-acceptance-contract.md) is
    open.** Define the security, testing, documentation, clean-clone build, and handoff gates.

## Tickets

| Ticket | Type | Mode | Status | Depends On | Gate |
|---|---|---|---|---|---|
| [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) | Research | AFK | **Open** | — | —; released v0.8.0 full-delta/schema audit remains open |
| [WF-002 — Local Development Runtime Contract](tickets/WF-002-local-development-runtime-contract.md) | Grilling | HITL | **Closed** | — | — |
| [WF-003 — ADR HTTP and OpenAPI Contract](tickets/WF-003-adr-http-openapi-contract.md) | Prototype | HITL | **Open** | WF-001 | — |
| [WF-004 — Doctrine Persistence and Bootstrap Contract](tickets/WF-004-doctrine-persistence-bootstrap-contract.md) | Prototype | HITL | **Open** | WF-001, WF-002 | — |
| [WF-005 — Authentication and Account Security Contract](tickets/WF-005-authentication-account-security-contract.md) | Grilling | HITL | **Open** | WF-003, WF-004 | — |
| [WF-006 — Principal and Authorization Mapping](tickets/WF-006-principal-authorization-mapping.md) | Grilling | HITL | **Open** | WF-001, WF-004, WF-005 | — |
| [WF-007 — Async, Scheduling, and Realtime Contract](tickets/WF-007-async-scheduling-realtime-contract.md) | Grilling | HITL | **Open** | WF-002, WF-004, WF-005, WF-006 | — |
| [WF-008 — Complete HTTP Operation Matrix](tickets/WF-008-complete-http-operation-matrix.md) | Grilling | HITL | **Open** | WF-003, WF-005, WF-006, WF-007 | — |
| [WF-009 — React SPA Architecture and Journeys](tickets/WF-009-react-spa-architecture-journeys.md) | Prototype | HITL | **Open** | WF-005, WF-008 | — |
| [WF-010 — Implementation Handoff Acceptance Contract](tickets/WF-010-implementation-handoff-acceptance-contract.md) | Grilling | HITL | **Open** | WF-002 through WF-009 | Human approval of the handoff |

## Blocking relationships

```text
released v0.8.0 audit ────→ WF-001 ──┬──→ WF-003 ──┐
                                   ├──→ WF-004 ──┼──→ WF-005 ──→ WF-006 ──┐
WF-002 ────────────────────────────┘      │      │                        │
  └───────────────────────────────────────────────────────────────────────┤
WF-004 + WF-005 + WF-006 ─────────────────→ WF-007 ───────────────────────┤
                                                                          └──→ WF-008 ──→ WF-009
WF-002 through WF-009 ───────────────────────────────────────────────────────────────────→ WF-010
WF-010 ──→ accepted EPIC, requirement TICKETs, and approved TASK handoff after lifecycle cutover
```

WF-002 is closed. WF-001 has audited AccessControl v0.4.0, but its schema findings prevent closure under the
retained package-owned schema requirement. Audit the installed v0.8.0 artifacts, fixes, regression results and
full contract delta before WF-003 or WF-004 can proceed. Installation does not substitute for that evidence.

## Frontier

[WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) is available for
research against installed v0.8.0 with Common v1.4.0. Resume release identity/compatibility checks, schema-fix and
drift regression verification, and the full public-contract delta. It remains Open until that evidence is accepted.
No duplicate bug ticket or new remediation-choice decision is needed. Once WF-001 closes, the next human decision is
[WF-003 — ADR HTTP and OpenAPI Contract](tickets/WF-003-adr-http-openapi-contract.md).

## Not yet specified (fog)

- WF-001 records the v0.4.0 public-contract delta. Schema fixes and drift regression tests in the current v0.8.0
  baseline remain unaudited here; downstream decisions stay blocked until released verification.
- Agent provisioning and subsequent workflows through v0.8.0 are not specified by this map. Audit their
  released public contracts and boundary classifications before updating downstream persistence, security, API,
  worker, or UI decisions; do not limit the follow-up to checking the old schema findings.
- WF-004 through WF-008 must consume the audited recoverable delivery, supported TransactionalUnitOfWork,
  non-null Permission tiers, shared reference/tier fences, and consumer-owned entry-point authorization changes.
  These released requirements do not settle the database, worker, security, or HTTP decisions.
- Runtime topology, image selection/pinning policy, host ports, health thresholds, volume policy, and worktree
  naming are settled in WF-002. Image manifests and running-runtime proof belong to implementation acceptance.
- Exact endpoint paths, schemas, permission names, pagination defaults, idempotency keys, rate limits, and OpenAPI
  one-pass generation inputs and normalized contract comparison remain downstream decisions.
- Exact persistence tables, indexes, lock strategies, transaction boundaries, migration sequence, and managed
  policy ownership remain downstream decisions.
- Exact browser session, JWT claim, asymmetric key, refresh reuse, CSRF/origin, email delivery, queue retry,
  scheduled cadence, Mercure topic, and subscription rules remain downstream decisions.
- Exact React libraries, page inventory, accessibility target, asset budgets, development-server behavior, and
  human UAT scripts remain downstream decisions.

## Out of scope

- Runtime or application implementation while this map is being charted.
- Copying or modifying Fight Common or Fight AccessControl source, publishing either package, or substituting an
  unreleased package revision for a required release tag.
- Production deployment topology, infrastructure-as-code, availability claims, release, publication, or public
  readiness certification.
- Public self-registration, a first-user HTTP backdoor, or unauthenticated administrator creation.
- A mandatory UI for machine-agent or operator-only capabilities.
- A shared frontend runtime package. Downstream starters receive an explicit source copy only after Symfony records
  the immutable accepted client reference; they do not consume Symfony backend code.
- Archiving this map or creating implementation records before the decision set and handoff are complete.
