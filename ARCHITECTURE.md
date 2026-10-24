# Architecture

Fight Symfony Starter is a Symfony-native application foundation, not the implementation home of Fight packages.
[Foundation](planning/FOUNDATION.md) distinguishes existing capabilities from planned integrations.

## Ownership and dependencies

Keep `App\` mapped directly to `src/`, with dependencies pointing `Adapter → Application → Domain`.
Preserve the existing source organization; introduce a layer or capability only when an accepted use case needs it.

- Domain owns business concepts, state, invariants, values and policy. It may use stable public package Domain
  types, but not Application, Adapter, framework, transport, persistence or external-service implementations.
- Application coordinates commands/queries through injected inward capabilities. It may consume public package
  Domain/Application contracts, but not concrete Symfony, persistence, HTTP or provider implementations.
- Adapter owns Symfony HTTP, security, persistence, presentation and external-service integration. Composition
  under `config/`, the Kernel and entrypoints may know concrete types; business code may not locate services.

Fight Common and Fight AccessControl are Composer packages. Use their public messages, handlers, events, safe
Views and capabilities directly when they express the use case. Never copy package behavior, reach into internals,
rename upstream APIs or introduce forwarding wrappers merely to hide their namespaces. Package changes belong
with their owners; dependency selection and integration belong here. Audit the installed contract before adding
bindings; another consumer's installed version is not this project's compatibility evidence.

## Owned orchestration

An owned Application use case is justified only when all of these are true:

1. An owning package does not already provide the behavior.
2. It enforces application-owned policy or coordinates real ownership boundaries.
3. It has observable input, outcome/failures and meaningful behavior tests independent of a transport.
4. It depends on injected inward capabilities, not concrete adapters or a service container.

Do not invent coordination for a presentation-only page. Keep state-based decisions with their Domain owner,
coordination in Application, and transport mapping in Adapter. CQRS implies no transaction, durable delivery,
retry, ordering, idempotency or exactly-once guarantee. Document each guarantee from the actual owning contract.

## Symfony composition

Symfony owns namespace loading, service definitions, aliases, autoconfiguration, compiler passes, environment
configuration and every persistence/presentation adapter. There is no Fight bundle. Keep the composition root in
`src/Adapter/Kernel.php`, `config/services.php`, and capability-scoped `config/common/` modules. These modules use
Symfony's `ContainerConfigurator`; do not import another framework's container, registration signatures or globs.

Reuse shared registered services, handlers and buses. Do not construct a parallel service graph or use a
container in Domain/Application code. Service-aware routers and compiler passes are composition machinery.
Dispatch package and owned messages through configured bus pipelines, including nested orchestration; an empty
filter list does not authorize direct handler calls. Registration is neither execution nor authorization.

`public/index.php` explicitly wraps the bare Kernel with Fight Common's Symfony JSON middleware. Console and
production boot checks use the bare Kernel. Keep test-only JSON routes and messaging fixtures out of production.
Existing lazy messaging visibility is owned adapter behavior, not permission to expose every service.

## Transport and future integrations

Use `handle()` on HTTP Actions and `respond()` on Responders under the [engineering standards](docs/engineering/STANDARDS.md#http).
This supersedes the earlier invokable-Action preference without changing current routes in this documentation slice.
The HTML homepage stays presentation-only. Exact API envelopes, errors, OpenAPI, authentication, persistence and
worker guarantees remain with their accepted local requirements and Wayfinder decisions.

New schemas, mappings, repositories, migrations, records and presentation models belong here, but implementation
requires an accepted, authorized TASK. Preserve legacy requirement authority under
[planning conventions](planning/CONVENTIONS.md#hierarchy-and-identities). No login, database, browser, release or
visibility transition follows from these architecture conventions alone.
