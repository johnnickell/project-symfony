# Symfony AccessControl Starter Application

**Label:** `wayfinder:map`
**Status:** Active

> This map is an **index, not a store**. Each material decision lives in exactly one linked ticket under
> `tickets/`; this map only summarizes the linked resolutions and shows the next decision frontier.

## Destination

Chart an implementation-ready, local-development Symfony starter built on the released
`johnnickell/fight-common` v1.2.0 and `johnnickell/fight-access-control` v0.2.0 package contracts. The planned
application composes MySQL, Redis, Nginx, PHP-FPM, PHP-CLI workers, Cron scheduling, private Mercure SSE, every
consumer-relevant AccessControl workflow, OpenAPI documentation, and an editable React SPA under `client/`
compiled into `public/dist/`.

The starter owns Symfony composition and every framework, persistence, HTTP, security, and presentation adapter.
It consumes the packages through their public Composer contracts only; it does not copy package Domain or
Application source or introduce a Fight bundle.

**Done** = every linked decision ticket is closed, the operation matrix accounts for every consumer-relevant
released AccessControl capability or explicitly excludes it as non-HTTP, all remaining fog is resolved or
excluded, and the map links to its resulting epic, PRDs, and executable implementation tickets.

## Notes

- This is a decision-only map. It introduces no application API, database schema, environment variable, package
  dependency, runtime behavior, or release claim.
- The Compose topology is certified for local development only. Deployment and production topology are separate.
- Initial administration is invitation-led, with an idempotent CLI bootstrap and no public first-user or
  self-registration backdoor.
- Mercure is the selected realtime transport. The SPA is editable starter source, not a shared runtime package.
- Fight AccessControl owns scan-only reusable schemas under `resources/openapi/`; Symfony owns the canonical paths,
  methods, operation IDs, wire semantics, authentication behavior, realtime event contract, and complete document.
- Symfony generates one OpenAPI 3.1 document in one pass by scanning the installed package schema resources and its
  own Actions, DTOs, routes, security declarations, and project-specific components. It never merges two specs.
- The handwritten client follows the public Fight CMS React/TypeScript/ESBuild/Sass structural baseline, becomes
  the immutable source-copy reference for downstream starters, and generates bindings from Symfony's local spec.
- Machine and operator capabilities must remain fully usable through the documented API or CLI without mandatory
  UI coverage.
- Private reference lessons may be translated into generic requirements, but private identities, paths, hashes,
  internals, and derivation claims must not enter public artifacts.
- Current Symfony documentation makes the proposed composition feasible: invokable controller services support
  the planned ADR shape; Scheduler schedules are consumed by Messenger workers; Redis Messenger transport uses
  streams and requires unique consumer names; and Mercure provides an SSE hub. See
  [controller services](https://symfony.com/doc/7.4/controller/service.html),
  [Scheduler](https://symfony.com/doc/current/scheduler.html),
  [Messenger](https://symfony.com/doc/current/messenger.html), and
  [Mercure](https://symfony.com/doc/current/mercure.html).
- WF-001 has [v0.1.0 baseline evidence](research/WF-001-released-package-contract-audit-research.md), but that
  release lacks reusable schema carriers. John retained package-owned schema information and selected forthcoming
  v0.2.0; project endpoint/document metadata remains Symfony-owned. Development revisions or an announced tag
  cannot satisfy the updated audit gate.

## Decisions so far

1. **[Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) is open.** The v0.1.0
   baseline is recorded; audit the forthcoming v0.2.0 schema resources and public-contract delta before closure.
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
| [WF-001 — Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md) | Research | AFK | **Open** | — | Installable `fight-common` v1.2.0 and `fight-access-control` v0.2.0 |
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
installable package releases ──→ WF-001 ──┬──→ WF-003 ──┐
                                   ├──→ WF-004 ──┼──→ WF-005 ──→ WF-006 ──┐
WF-002 ────────────────────────────┘      │      │                        │
  └───────────────────────────────────────────────────────────────────────┤
WF-004 + WF-005 + WF-006 ─────────────────→ WF-007 ───────────────────────┤
                                                                          └──→ WF-008 ──→ WF-009
WF-002 through WF-009 ───────────────────────────────────────────────────────────────────→ WF-010
WF-010 ──→ epic, PRDs, and executable implementation tickets
```

WF-002 is closed. WF-001 remains gated on installable AccessControl v0.2.0 with reusable schema
information and a verified delta audit. Common v1.2.0 and the AccessControl v0.1.0 baseline are verified; a
development branch, alias, candidate commit, or unpublished package tree is not substitute evidence.

## Frontier

No decision is currently takeable. [Released Package Contract Audit](tickets/WF-001-released-package-contract-audit.md)
awaits installable AccessControl v0.2.0 schema carriers and a verified delta audit. Once it closes, the next
human decision is [ADR HTTP and OpenAPI Contract](tickets/WF-003-adr-http-openapi-contract.md).

## Not yet specified (fog)

- AccessControl v0.1.0 capabilities are recorded in WF-001 research. The v0.2.0 public-contract delta and reusable
  schema carriers remain unverified; WF-003 and downstream decisions must wait for that audit.
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
