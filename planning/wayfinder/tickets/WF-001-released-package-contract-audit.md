# WF-001 — Released Package Contract Audit

**Labels:** `wayfinder:research`
**Mode:** AFK
**Status:** Open
**Gate:** Installable `fight-common` v1.2.0 and `fight-access-control` v0.2.0, including reusable schema carriers
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** —

## Question

What complete, consumer-relevant public contract do the requested released package versions expose, and which
capabilities belong at an HTTP, CLI, worker, or composition-only boundary in this Symfony starter?

## Must decide

- Audit the installed release artifacts, public documentation, and exported symbols without treating package
  internals or development revisions as consumer authority.
- Inventory every consumer-relevant authentication, user, role, permission, grant, session, managed-policy,
  machine-agent, notification, audit, transaction, and publication capability.
- Classify each capability as HTTP, CLI, worker, composition-only, or explicitly unsupported in this starter.
- Record required ports, value objects, commands, queries, events, results, errors, and semantic guarantees that
  downstream adapter decisions must preserve.
- Identify package-version compatibility constraints and genuine omissions without inventing project-owned domain
  behavior to fill them.

## Resolution boundary

This ticket may settle the authoritative released consumer surface and boundary classification. It may not choose
Symfony routes, database mappings, security middleware, queue topology, UI coverage, or implementation tickets.
It cannot close from a development branch, Composer alias, candidate commit, private working tree, or inferred
future release contents.

## Resolution

Open. The [v0.1.0 baseline audit](../research/WF-001-released-package-contract-audit-research.md) verifies public
release identity and consumer contracts against Fight Common v1.2.0. AccessControl v0.1.0 lacks the required
reusable schema carriers. John retained that requirement and selected forthcoming v0.2.0 on 2026-09-12.

The package supplies reusable **schema information only**, avoiding duplicated model schemas across starters.
It does not need to carry every OpenAPI attribute or any project paths, methods, operation IDs, endpoint security,
responses, servers, or document metadata. Those remain Symfony-owned under WF-003.

Resume when v0.2.0 is installable: verify its immutable identity and shipped schema resources, then audit all
public-contract deltas from the baseline. Do not close this ticket or WF-003 from the announced release alone.
Clean root Composer resolution, release pinning, candidate-receipt/lock removal, and build-process cleanup remain
in the later standalone-adoption slice.
