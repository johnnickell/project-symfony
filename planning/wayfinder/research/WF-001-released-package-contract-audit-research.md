# WF-001 — Released Package Contract Audit Evidence

Research date: 2026-09-12. Authority: public immutable release trees, remote tag refs, and Packagist metadata.
This is evidence for [Released Package Contract Audit](../tickets/WF-001-released-package-contract-audit.md),
not proof that this starter has adopted or booted the releases.

## Release identity and limits

| Package | Baseline release | Annotated tag object | Peeled source commit |
|---|---|---|---|
| Fight AccessControl | v0.1.0 | `1281571f8bbfe9f98b03284c584c549204728e38` | `421961273062d150239f65b3e2131e45c6ceda44` |
| Fight Common | v1.2.0 | `8745e719afb2448c59d7c3f6d0e2f577b5e99bce` | `a2cd615d9b5064c9c30e994655536176249cd73b` |

Successful remote `git ls-remote` queries matched the local peeled tags. Public
[AccessControl metadata](https://repo.packagist.org/p2/johnnickell/fight-access-control.json) and
[Common metadata](https://repo.packagist.org/p2/johnnickell/fight-common.json) advertised matching source and dist
references. Both tagged manifests require PHP >=8.5; AccessControl's Common `^1.1` constraint admits v1.2.0.
These checks establish release identity and manifest compatibility. They do not establish clean root Composer
resolution, optional adapter dependencies, container boot, or passing consumer conformance.

The starter still uses development dependencies and candidate-specific verification. The later standalone-adoption
slice owns released Composer pinning, one clean lock resolution, removal of latest/lowest lock artifacts and
candidate receipts, and the corresponding build-process cleanup. None of those files changes during charting.

### Required carrier gap and revised target

The complete v0.1.0 tree contains no `resources/` directory, OpenAPI document, or Swagger schema carriers.
On 2026-09-12 John retained package-owned carriers as a requirement and selected a forthcoming AccessControl
v0.2.0 release to supply reusable schema information only. Symfony continues to own all endpoint and
complete-document metadata; the package is not expected to annotate project operations. v0.1.0 is therefore audited baseline evidence, **not the completed target audit**.
WF-001 remains Open pending remote v0.2.0 identity, installable metadata, carrier inspection, and a public-contract
delta audit. WF-003 and its dependent decisions remain blocked. Do not copy schemas into Symfony to bypass this
gate, infer the future release contents, or treat a pushed development branch as release evidence.

## Public capability inventory and boundary classification

Names below refer to public contracts in the
[released source tree](https://github.com/johnnickell/fight-access-control/tree/421961273062d150239f65b3e2131e45c6ceda44/src).
HTTP classification identifies a consumer boundary for later WF-008 design; it does not create routes or require UI.

| Capability | Released public commands, queries, or service methods | Consumer boundary |
|---|---|---|
| User administration | `InvitePendingUser`, `CorrectPendingInvitation`, `DisableUser`, `EnableUser`, `DeleteUser`, `RestoreUser`; `GetUserById`, `ListUsers` | HTTP; invitation-led initial administration also CLI |
| Invitation delivery | `DeliverUserInvitation`, `RetryInvitationDelivery`, `ResendInvitationDelivery`; `FindInvitationDeliveryStatus` | Worker delivery; authorized HTTP retry/resend/status; CLI recovery |
| Authentication | `AuthenticationService::activate`, `login`, `refresh`, `logout` | Synchronous HTTP; secrets never serialized into CQRS messages |
| Password lifecycle | `AuthenticationService::changePassword`, `resetPassword`; `RequestPasswordReset`, `ConfirmPasswordResetDelivery`, `ExpirePasswordResetDelivery` | HTTP request/change/reset; worker confirmation and scheduled expiry |
| Email lifecycle | `RequestEmailChange`, `CancelEmailChange`, `DeliverEmailChange`, `ExpireEmailChange`; `AuthenticationService::confirmEmail` | HTTP request/cancel/confirm; worker delivery and scheduled expiry |
| Sessions | `ListActiveSessions`, `RevokeSession` | HTTP self-service or reasoned authorized administration; one revocation target |
| Custom roles | `CreateCustomRole`, `RenameCustomRole`, `RemoveCustomRole`, `GrantPermissionToCustomRole`, `RevokePermissionFromCustomRole`; `GetRoleById`, `ListRoles` | Authorized HTTP; managed roles excluded from mutable custom-role operations |
| User roles | `AssignRoleToUser`, `RemoveRoleFromUser` | Authorized HTTP |
| Permissions and managed policy | `GetPermissionById`, `ListPermissions`; `PreviewManagedPolicy`, `ReconcileManagedPolicy` | HTTP reads; version-controlled policy preview/apply through CLI |
| Agents | `AgentProvisioningService::provision`; `AgentCredentialLifecycleService::rotate`, `revoke`; `GrantPermissionToAgent`, `RevokePermissionFromAgent`, `ReplaceAgentPermissions`; `GetAgentById`, `ListAgents` | Operator CLI for secret issuance/rotation; authorized API for safe administration; exact matrix belongs to WF-008 |
| User request authority | `AuthenticationContextProvider`, `AuthoritativePrincipalResolver`, request-scoped `CurrentPrincipalProvider` | Composition-only; no client-supplied permission snapshots |
| Agent request authority | `CurrentAgentPrincipalProvider::resolve`, `SignedAgentRequest`, `SecurityContext` | Composition-only; distinct HMAC agent authority |
| Lifecycle-wide revocation | `SessionRevocationService::revokeAllActiveFor` | Composition-only security lifecycle orchestration; no bulk self-service journey |
| Delivery/event subscribers and common dispatch | Public event subscribers, command/query buses, event dispatcher | Worker/composition-only; no raw public event stream |

No generic account update, public self-registration, tenant/organization model, user direct-permission assignment,
agent roles, device/browser labels, or bulk session-management command is supplied by this audited surface.
Do not infer support from older specification prose when the released public contract is narrower.

## Results, failures, and semantic guarantees

- Commands normally return `void`; queries return nullable safe views or `ResultSet`/`Pagination`, never aggregates.
  Invitation status has `InvitationDeliveryStatusView`; policy preview returns deterministic `ManagedPolicyPlan`.
- Activation/login return secret-bearing `TokenSet`. Refresh returns `ROTATED` with a token set or bounded
  `CONFLICT` without credentials; terminal reuse revokes the session family. Agent provisioning/rotation returns
  explicitly secret-bearing results; ordinary `AgentView` excludes the shared secret.
- Public failure classes distinguish denied authority, missing resources, invalid lifecycle transitions,
  uniqueness/revision conflicts, expired credentials, replay, and rejected authentication. Symfony must map these
  to safe transport outcomes in WF-003/WF-008 without exposing raw exception text.
- Account mutation, required secret-free audit evidence, and compare/replace effects must be atomic. Handlers own
  transactional boundaries and emit success events after commit. Post-commit publication alone cannot supply
  required audit durability.
- Password change/reset and confirmed email change revoke sessions through package lifecycle orchestration.
  Enablement does not restore revoked sessions; soft deletion does not release the canonical email identity.
- Actor IDs, current-session IDs, and authentication versions come from trusted request authority. Expected
  revisions come from safe authoritative views; the server verifies them atomically.
- The supported profile uses 15-minute access JWTs; ordinary refresh sessions have one-day idle/two-day absolute
  lifetimes, remembered sessions 15-day idle/30-day absolute lifetimes. The refresh-conflict window is explicitly
  consumer configured. A bounded refresh conflict is not terminal replay and must not clear the winning cookie.

## Security Sessions evidence

[SessionView](https://github.com/johnnickell/fight-access-control/blob/421961273062d150239f65b3e2131e45c6ceda44/src/Domain/AccessControl/RefreshSession/Query/SessionView.php)
exposes exactly `session_id`, `user_id`, `created_at`, `last_activity_at`, `idle_expires_at`,
`absolute_expires_at`, `remembered`, and `current`. Last activity means refresh activity, not arbitrary browsing.
There are no device/browser labels.

[RevokeSession](https://github.com/johnnickell/fight-access-control/blob/421961273062d150239f65b3e2131e45c6ceda44/src/Domain/AccessControl/RefreshSession/Command/RevokeSession.php)
selects one target. Its
[handler](https://github.com/johnnickell/fight-access-control/blob/421961273062d150239f65b3e2131e45c6ceda44/src/Application/AccessControl/RefreshSession/CommandHandler/RevokeSessionHandler.php)
rejects the current session, unusable/missing targets, and concurrent replacements. Cross-user revocation requires
authorization and an audited reason. The lifecycle service's bulk method does not authorize a revoke-all UI.

## Required consumer ports and Common compatibility

- Repositories: User, Role, Permission, Agent, ActivationGrant, PasswordResetGrant, EmailChangeGrant,
  RefreshSession, and AuditEvidence. Contracts require atomic compare/replace, revision checks, canonical-email
  uniqueness and reservation handling, reference checks, and grant succession.
- Application seams: Clock, LoginThrottle, purpose-specific credential generators, delivery encryption/invocation,
  and session/invitation/email/role/user-role/agent-permission administration authorization.
- HMAC seams: shared-secret generator/cipher/decipher, signed-request verifier, and atomic nonce consumption.
- Common seams: command/query dispatch, event dispatch, password hashing/validation, JWT encode/decode, and the
  transactional unit of work. See the
  [Common Symfony guide](https://github.com/johnnickell/fight-common/blob/a2cd615d9b5064c9c30e994655536176249cd73b/docs/frameworks/symfony/index.md).

AccessControl v0.1.0 constructors require `Application\Repository\UnitOfWork`. Common v1.2.0 retains that
deprecated interface, extending `TransactionalUnitOfWork` and additionally requiring `commit()`.
`DoctrineTransactionalUnitOfWork` alone does not implement that legacy interface. The retained
[DoctrineUnitOfWork](https://github.com/johnnickell/fight-common/blob/a2cd615d9b5064c9c30e994655536176249cd73b/src/Adapter/Repository/DoctrineUnitOfWork.php)
or a deliberately compatible consumer adapter is required; WF-004 must audit the v0.2.0 delta before choosing.

Symfony owns namespace loading, autoconfiguration, selected compiler passes, aliases, environment configuration,
request scope, all framework adapters, and runtime composition. No Fight bundle or copied package source is needed.

## Evidence required to complete WF-001

1. Verify and record AccessControl v0.2.0 remote peeled tag and installable metadata.
2. Inspect its shipped schema carriers and confirm scan-only scope, dependencies, and OpenAPI compatibility.
3. Compare all public commands, queries, services, ports, results, and exceptions with this baseline.
4. Update boundary classifications and downstream assumptions for every material delta.
5. Preserve clean Composer adoption/build proof as later implementation work; do not substitute that caveat for
   the missing release/carrier audit.
