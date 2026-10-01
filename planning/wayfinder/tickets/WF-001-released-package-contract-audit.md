# WF-001 — Released Package Contract Audit

**Labels:** `wayfinder:research`
**Mode:** AFK
**Status:** Open
**Gate:** Installable AccessControl v0.5.0, then verify schema fixes, regression evidence, and the full released-contract delta including new Agent workflows
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

Open. The [v0.4.0 delta audit](../research/WF-001-v0.4.0-contract-delta-research.md) verifies release identity,
published installable metadata, declared Common v1.2.0 compatibility, and shipped schema resources, but identifies
a schema mismatch. John's subsequent upstream plan below supplies the disposition; released verification remains pending. The [v0.1.0 baseline audit](../research/WF-001-released-package-contract-audit-research.md) verifies public
release identity and consumer contracts against Fight Common v1.2.0. AccessControl v0.1.0 lacks the required
reusable schema carriers. John retained that requirement and selected forthcoming v0.2.0 on 2026-09-12.

The package supplies reusable **schema information only**, avoiding duplicated model schemas across starters.
It does not need to carry every OpenAPI attribute or any project paths, methods, operation IDs, endpoint security,
responses, servers, or document metadata. Those remain Symfony-owned under WF-003.

John subsequently reported that AccessControl v0.4.0 is released, with v0.5.0 forthcoming, and approved
retargeting this audit to v0.4.0. This supersedes the v0.2.0 target and release wait, not the schema requirement
or verification gate. At that stage, v0.5.0 was not a prerequisite. The subsequent upstream resolution plan below
now makes its released artifact the next audit target, without claiming adoption or verified fixes.

### v0.4.0 audit outcome — 2026-09-28

- Remote peeled tag and Packagist source/dist agree on `380b134b35c722e6416787b13f5c20c64bd05388`.
  The published distribution was retrieved and all 596 file blob hashes match the immutable tree. PHP >=8.5 and
  Common ^1.2 are declared requirements; this is not a clean consumer Composer install or boot claim.
- The scan-only catalog has 85 schema anchors under `openapi/`, loaded through `openapi/bootstrap.php`;
  `resources/openapi/` was a planning assumption, not the shipped public path.
- **F-01:** `InvitationDeliveryStatus` advertises old `failed`/`confirmed` values and rejects four current values
  exported by the released view: `retry_pending`, `delivered`, `permanent_failure`, and `invalidated`.
- **F-02:** the catalog lacks the new credential-delivery command, queries, and result shapes. Worker-only
  exclusions may be appropriate, but the coverage decision is not settled, especially for operational status.
- The linked audit accounts for all 29 Commands and 13 Queries, inventories events and changed ports/results,
  and records recoverable delivery, TransactionalUnitOfWork, Permission-tier, and consumer entry-policy deltas.
  It records source/static verification limits; no generator, package tests, or consumer runtime was executed.

### Upstream resolution plan and resume condition

John reports that AccessControl v0.5.0 will address F-01/F-02, add regression tests against future OpenAPI drift,
and introduce new Agent provisioning and associated workflows. Upstream work is already planned and in progress;
the bugfix TASK is on an isolated worktree. This is owner-reported planning, not inspected implementation,
accepted regression evidence, or a published release. No upstream TASK ID, PR, or worktree path was supplied.
Do not create duplicate bug work, modify that worktree, or infer authority to publish the package.

The owner disposition is package correction, retaining package-owned schemas. Await installable v0.5.0, then:

1. Verify immutable release identity, source/dist metadata, shipped resources, and Common v1.2.0 compatibility.
2. Recheck F-01's schema/enum parity and F-02's catalog coverage or explicit exclusions; inspect the added drift
   regression tests and their actual verification evidence. Planned tests alone do not establish a passing result.
3. Audit the full v0.4.0-to-v0.5.0 public-contract delta, not just the bugfix. Inventory the new Agent provisioning
   and associated workflows, changed ports, messages, results, errors, security guarantees, and reusable schemas.
   Reclassify HTTP, CLI, worker, and composition-only capabilities from released evidence, without assuming the
   existing Agent surface or boundary classification remains sufficient.
4. Verify applicable schema composition and record downstream implications before deciding closure. Keep WF-001
   Open and WF-003/dependent decisions blocked until the release audit satisfies the retained gate.

The next action is upstream completion/release followed by this audit, not another request to choose a remediation
strategy. Do not copy schemas into Symfony, treat the isolated worktree as release authority, or infer endpoint,
persistence, security, or UI decisions from the announced Agent workflows.

Clean root Composer resolution, release pinning, candidate-receipt/lock removal, and build-process cleanup remain
in the later standalone-adoption slice. No package fix, publication, or application implementation is authorized.
