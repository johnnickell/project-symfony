---
id: T-00011
epic: EPIC-00001
title: Align Owned PHP and Homepage Presentation
status: needs-info
blocked_by: T-00009,T-00010
approved: yes
---

# Align Owned PHP and Homepage Presentation

## Outcome

Make existing Symfony-owned PHP demonstrate the accepted engineering conventions and give the homepage explicit
Action/Responder responsibilities while preserving its observable behavior and package/framework boundaries.

## Approval and execution boundary

John approved this requirement area under [EPIC-00001](../epics/00001-EPIC.md). It groups requirements, not a
single prescribed PR or an implementation TASK. Planning cutover is complete; `needs-info` records the missing
accepted TASK handoff and unfinished predecessor outcomes. Implementation follows [T-00009](00009-TICKET.md) and
[T-00010](00010-TICKET.md); their requirements and this split are approved, but no implementation assignment is implied.

## Scope

### Included use cases

- A visitor requests `GET /` and receives the same successful HTML homepage, including the configured application
  name, route behavior, and rendered content. No new input contract or visual redesign is introduced.
- A maintainer follows a final invokable Action for the homepage interaction and an explicit Responder owning
  successful status, headers, and rendering. Constructor injection makes dependencies visible instead of locating
  them through the controller container.
- A contributor reads or changes existing owned PHP and finds the accepted strict-types, final/readonly, naming,
  namespace, and documentation conventions, or a specific justified local exception.
- Existing web entrypoint JSON middleware, test-owned JSON journey, console/bare-Kernel composition, messaging
  visibility behavior, and public-package bindings remain intact while affected owned code is aligned.

### Affected adapters and documentation

The homepage controller/action and rendering adapter, route/service composition required for that change, other
existing owned PHP within the bounded convention inventory, and directly affected Unit/Functional tests are in
scope. Inventory source and relevant test conventions before proposing individual TASKs; do not bulk-move code
just to resemble a diagram. Update architecture/contributor examples that name the old presentation structure.

A purely presentational homepage requires no command, query, application handler, repository, or Domain layer.
Responder dependencies may render the supplied presentation context; they must not fetch missing business data or
make policy decisions. Public package class/interface names remain unchanged, even when local taste differs.

### Exclusions

New HTTP interactions, visual redesign, invented CQRS messages, business policy, authentication/permissions,
persistence, dependency upgrades, copied package behavior, arbitrary namespace reshuffling, and imposing JSend on
HTML. Exact API envelope/error/OpenAPI decisions remain with
[WF-003](../wayfinder/tickets/WF-003-adr-http-openapi-contract.md). Portfolio-wide test retirement belongs to T-00012.

## Acceptance Criteria

- [ ] AC-01 — `GET /` preserves the existing route, successful status, HTML representation, configured application
      name, and rendered behavior; no new browser or JSON contract is introduced.
- [ ] AC-02 — A final invokable Action represents the homepage interaction and delegates presentation to an explicit
      Responder. Status, headers, and rendering belong to the Responder; dependencies are constructor-injected.
- [ ] AC-03 — No fake use case, CQRS message, business layer, persistence access, policy decision, or transaction
      machinery is introduced for this presentation-only interaction.
- [ ] AC-04 — The bounded inventory of existing owned PHP and affected tests conforms to the accepted rules or
      records specifically accepted exceptions. Public-package names and vendor source remain untouched.
- [ ] AC-05 — Explicit front-controller JSON middleware and bare-Kernel console composition remain intact; affected
      messaging visibility/provider conventions retain their previous observable contracts.
- [ ] AC-06 — Direct owned-behavior tests and real HTTP integration establish the changed seams and preserved output,
      not merely mock call order. Meaningful failure/rejection cases are covered where applicable or explained.
- [ ] AC-07 — Installed-standard PHPCS, PHPStan, Deptrac, Rector, exact Unit coverage, and retained journeys pass
      through the canonical gate without wider exclusions or suppressed compatibility failures.
- [ ] AC-08 — Documentation/examples match the new structure and independent Spec and Standards review verifies
      ownership, convention compliance, preservation of behavior, and the absence of speculative abstractions.

## Verification

During later authorized implementation:

- Capture the baseline homepage response and configured-name behavior; compare them with the real response after
  the change. Use focused Unit and HTTP tests for the Action/Responder contract, then retained integration journeys.
- Verify web JSON composition and bare-Kernel console behavior through the actual owned entrypoints; do not infer
  those guarantees from a mocked rendering call or add a production-only test route.
- Review the convention inventory and exceptions against T-00009. Inspect public imports, injection, and each
  responsibility directly where static tools cannot establish intent.
- Run `./bin/planning-check`, `git diff --check`, and `./bin/build`, retaining the separate Unit artifact and actual
  test counts/warnings. Obtain independent review of the authorized TASK snapshots.

## Applicability

No new business commands, queries, events, permissions, or request validators are needed: the homepage remains an
existing presentation-only GET interaction. Do not fabricate invalid-input or authorization cases for absent
features; record why those categories do not apply. Preserve current escaping and safe output, avoid leaking
secrets through diagnostics, and do not introduce server authority from client input. Existing Composer package
contracts are assumptions to preserve, not upgrade targets.

## Implementation TASKs

<!-- planning:children -->
| Order | Record | Parent | Status | Blockers / gates | PR |
| --- | --- | --- | --- | --- | --- |
| — | None | — | — | — | — |
<!-- /planning:children -->

## Completion Notes

Requirements approved and recorded only. No source refactor, new Action/Responder, TASK, or implementation
verification has been performed; predecessor outcomes and a separately accepted TASK handoff remain outstanding.
