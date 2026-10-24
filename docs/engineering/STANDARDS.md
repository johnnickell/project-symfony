# Engineering standards

These rules govern owned application changes. [Architecture](../../ARCHITECTURE.md), accepted local requirements
and public package contracts supply use-case decisions. Shared skills use these rules rather than importing
another application's framework or product behavior. Current code can require separately scoped alignment;
writing a rule is not evidence that its migration is complete.

## Architecture and interfaces

Design for human maintainers first. Dependencies point `Adapter → Application → Domain`. Use package contracts
directly and apply the [owned-orchestration test](../../ARCHITECTURE.md#owned-orchestration) before introducing an
Application use case. Keep business policy with its Domain owner; never reconstruct it in transport code.

Name capability interfaces for behavior, without an `Interface` suffix. Use demonstrated boundaries rather than
speculative Service, Manager, Helper or generic value buckets. Group cohesive responsibilities by capability;
class count is a signal, not a folder limit. Do not bulk-move `src/*` or add one-class layers to imitate a diagram.
Public package names are immutable consumer inputs, even when their spelling differs from local taste.

Prefer small, coherent interfaces that hide substantial implementation complexity. Consider inputs, results,
errors, configuration and call ordering, not just method count. Compare actual caller obligations before and
after a proposal. Do not hide authorization, transactions or effects behind opaque options, merge unrelated
owners, or create aliases around package use cases. Compatibility changes need an explicit migration path.

## Composition, buses and effects

Use Symfony constructor injection and shared registered services. Keep container access in composition machinery,
not Domain/Application. No parallel unregistered service graph, ambient trust flag or alternate privileged bus.
Every command/query dispatch traverses its configured pipeline, including nested work; inject buses, not handlers.

Commands express intent and may mutate state; queries return data without business mutation; events report facts
already established. Keep messages free of transport details and preserve package-owned events without renaming.
A void command result does not prove filters allowed all expected effects.

The owning state-changing use case defines its transaction through the installed public capability. Required
business state, audit and durable delivery intent must be atomic when the accepted requirement promises it.
Do not perform irreversible external effects inside that transaction or claim exactly-once delivery. Dispatch
post-commit effects and document recovery/retry/idempotency from actual behavior. Report an unsupported package
contract rather than privately calling handlers or weakening the guarantee. This rule authorizes no new persistence.

Console adapters validate/map transport input, dispatch through injected buses and present safe output/exit status.
Keep orchestration inward. Sanitize parsing errors, suggestions, verbose errors and handler failures; never echo
private input or raw exceptions. Registration does not authenticate an invocation.

## HTTP

Use Action–Domain–Responder. One final Action represents one interaction and exposes `handle()` as its sole HTTP
entrypoint. Its Responder exposes `respond()` and owns status, headers and representation. Bind Symfony routes to
the Action's `handle()` method; do not import Slim callable syntax or PSR response conventions into HttpFoundation.
This replaces the earlier invokable-Action preference. Existing homepage refactoring remains T-00011 scope.

- Actions map validated input and trusted actor/target context, dispatch one package or justified owned use case,
  and delegate presentation. No business policy, persistence, transaction control or external-provider work.
- Responders receive safe Views or explicit presentation context. They do not fetch missing business data or make
  policy decisions. Do not expose entities, hashes, credentials, grants or persistence models.
- A purely presentational HTML page needs no invented command, query, handler or Domain layer. Preserve its route,
  content, escaping and configured application name. Do not impose JSend on HTML.
- Keep PHP properties camelCase; explicitly map JSON and database fields to snake_case where those contracts exist.
- Authentication/transport gates are early checks, not the sole policy authority. Enforce permission, ownership and
  target decisions against authoritative state on every entry path. Role names, UI reachability, service presence
  and client-provided authority do not grant access. Do not duplicate package-owned invariants.
- Sanitize public errors. A generic lookup failure is not automatically a public 404. Exact envelopes, validation,
  error/OpenAPI and security semantics remain with WF-003 and downstream requirements.

Where supported metadata is used, group attributes in source order: OpenAPI → input source → Validation → access
requirements → other metadata, one attribute block each. Preserve repeatable-group ordering and supported targets;
Responder metadata normally describes responses. This is presentation order, not security execution order.

## PHP and naming

Use strict types, constructor injection and guard clauses. Prefer final classes, readonly properties and readonly
value objects; record justified framework/entity exceptions. Name messages for intent/fact (`RegisterUser`,
`GetUserById`, `UserRegistered`) and handlers with `Handler`. Use Fight helpers where they improve clarity.

Use multiline docblocks with capitalized active-verb summaries and no terminal period. Constructor summaries use
`Constructs ClassName`; inherited behavior uses multiline `@inheritDoc`. Retain the installed Fight Common PHPCS
standard, never modify vendor source to enforce local taste, and resolve genuine enforcement conflicts explicitly.

## Tests and coverage

Ordinary features need not follow TDD: settle interfaces and ownership, implement testable behavior, then prove it.
For bugs, reproduce with a failing regression before repair where technically possible; record a concrete reason
otherwise. Tests assert observable results, rejection, failure, state or effects against independently derived
expectations. Do not merely copy production output or mock the complete implementation call graph.

Choose the narrowest boundary that proves the contract: unit behavior, meaningful package/adapter integration,
and functional HTTP journeys. Preserve legitimate interaction assertions, safe mapping and side-effect ordering.
Missing categories need scope-grounded explanations, not invented production routes or fixtures.

Do not add product tests of tools, wrappers, planning, CI, configuration, static analysis, generated artifacts,
migrations or test infrastructure. Validate these directly with owning commands; never seed invalid source to
prove a tool catches it. Existing tests need individual contract-based disposition and verified equivalent
replacement before removal. Owned compiler-pass visibility behavior is not automatically disposable wiring.

**Exact 100% Unit-only owned-production statement coverage remains mandatory.** Report covered/total statements,
driver, metadata, exclusions, warnings and actual gaps. The current source denominator is `src/` excluding only
`src/Adapter/Kernel.php`; retain `requireCoverageMetadata` and production coverage-ignore rejection. Do not widen
exclusions, weaken assertions, or substitute integration/combined coverage. Coverage is a completeness aid, not
proof of correctness. Preserve meaningful Integration and Functional journeys separately.

[Build](BUILD.md) owns current commands and artifact limitations. Future gate changes must preserve a distinct
Unit artifact; an all-suite execution is acceptable only with independently attributable Unit-only proof.

## Code and documentation quality

Challenge duplication by knowledge ownership and reason to change, not similar syntax. Check framework discovery,
configuration, reflection and public consumers before declaring code unused. Preserve necessary compatibility
readers, trust-boundary checks and recovery paths. Separate taste from demonstrated defects.

Update affected instructions, examples and README claims with behavior. Keep implemented, planned, optional and
unverified capabilities distinct. Use one authority with linked callers, not duplicate policies. Rename/move
existing guidance and repair references together; preserve historical evidence and source identities. No new
changelog, version or public-readiness claim is implied by a documentation cleanup.

## Other languages and interfaces

Do not import another application's React, SCSS, database or provider stack into this Symfony starter. Introduce
frontend conventions only with an accepted local feature. For any future owned Pi/TypeScript module, use
capability-named kebab-case files, `-extension.ts` only for manifest entries, `-integration.ts` for internal
registration boundaries, camelCase functions and PascalCase types. Preserve command/tool IDs and operator state
when migrating paths; qualify actual installed APIs and restart requirements rather than pinning Pi versions.
