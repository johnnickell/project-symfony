# WF-009 — React SPA Architecture and Journeys

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-005, WF-008

## Question

Which React, TypeScript, ESBuild, routing, styling, and API-client structure—using the public Fight CMS client
layout as a structural baseline—gives the starter complete editable human workflows while preserving the settled
token and authorization boundaries? This is a frontend decision: it must not select Symfony adapters, HTTP Actions
or Responders, console commands, persistence, mail, queues, or runtime composition.

## Must decide

- Prototype the source topology under `client/`, TypeScript boundaries, ESBuild entrypoints and chunks, Sass
  ownership, static assets, development workflow, and deterministic output under `public/dist/`.
- Define in-memory access-token ownership, startup refresh, single-flight automatic refresh, request replay limits,
  logout, expiry, tab behavior, and navigation after authentication-state changes; never persist bearer tokens in
  browser storage.
- Define routing, authenticated and permission-aware layouts, loading/error/empty states, form validation,
  accessibility, responsive behavior, and server fallback for client-side routes.
- Set the white-label access experience: a Bootstrap 5.3 structural base with project-owned semantic tokens,
  native System/Light/Dark color modes, and a small component layer. Tabler may inform restraint, spacing, and
  responsive composition, but no admin-template dependency or source may be copied into the starter.
- Define the first human journeys without inventing product scope: safe internal return handling for anonymous
  redirects; login; enumeration-resistant two-step password recovery; the intentionally sparse authenticated
  Dashboard; and Security Sessions, including confirmation before revoking one other session.
  Current-session revocation must remain unavailable from that page.
- Keep branding structural rather than forked: an application name, replaceable monochrome mark, and tokenized
  visual layer must let an adopter rebrand without replacing layout code. Reserve color for accessible semantic
  meaning; include paired text, border, and subtle-surface tokens in both color modes, visible keyboard focus,
  reduced-motion behavior, semantic validation announcements, and responsive one-column authentication layouts.
- Define the initial frontend route and component inventory for login, recovery, Dashboard, Security Sessions, and
  the surrounding account menu; defer profile, users, roles, permissions, administration, and product features
  until their own journeys are settled.
- Define generated or typed API-client boundaries, OpenAPI drift checks, realtime subscription/refetch behavior,
  and test seams without selecting server-side endpoint, controller, transport, or console implementations.
- Identify machine/operator operations that intentionally remain API- or CLI-only and do not require decorative
  screens.
- Record the immutable accepted Symfony source reference and copy manifest that downstream starters use after the
  wire contract is settled; generated API bindings always come from Symfony's own OpenAPI document here.
- Define the downstream adaptation boundary as environment/configuration and framework-facing integration only,
  with normalized client-facing contract and source-drift checks for substantive divergence.

## Resolution boundary

This ticket may use disposable frontend prototypes to settle architecture and journeys. It may not create the
production client, compiled assets, package dependencies, or shared runtime frontend. It may define—but not perform
during charting—the reviewed source-copy handoff to downstream starters.

Backend adapter, HTTP/OpenAPI Action and Responder, console, persistence, mail, queue, and runtime-composition
decisions are intentionally out of scope for WF-009. They remain in WF-002 through WF-008 in this existing map;
WF-009 consumes their public client contract. No separate backend map is required.

## White-label access UX direction

The resolved visual-and-journey direction is deliberately narrow and must be implemented only after the settled
WF-005 security semantics and WF-008 operation matrix make each client operation real.

- Anonymous client routes redirect to Login and preserve only a validated internal return destination. Login is a
  centered authentication surface with configurable neutral wordmark and abstract monochrome mark, email and
  password fields, accessible inline validation, generic safe failure messaging, recovery link, and a
  System/Light/Dark selector. “Remember this browser” appears only if WF-005 defines its secure session and cookie
  semantics.
- Password recovery has a request form that always acknowledges submission without revealing account existence,
  followed by a reset form that collects and confirms a new password and returns to Login. Invalid or expired reset
  outcomes use WF-005's safe failure contract.
- The authenticated shell contains only a slim top bar, wordmark, active Dashboard item, theme control, and account
  menu. Dashboard is a calm signed-in confirmation with starter-oriented copy; it contains no metrics, charts,
  activity feed, invented quick actions, or placeholder product data.
- Security Sessions presents the current session and other sessions using only the safe fields supplied by the
  released `SessionView`: creation time, last refresh activity, idle and absolute expiry, remembered status, and
  current-session badge. It offers confirmation before revoking one other session and protects the current session
  from that page. There are no device/browser labels and no bulk-revocation control. Last activity describes
  refresh activity, not arbitrary browsing. Session identifiers remain opaque client keys.
- Bootstrap 5.3 components are the structural base. Project-owned CSS variables provide grayscale-first canvas,
  elevated surfaces, crisp borders, near-black typography, semantic success/warning/danger/information variants,
  and a persisted System/Light/Dark override whose default follows `prefers-color-scheme`. The implementation must
  meet WCAG AA contrast and test behavior rather than inspect CSS tooling or Bootstrap configuration.

### Contract and capability gate

WF-005 remains authoritative for remember-me/session lifetime, refresh rotation, CSRF and origin defenses,
throttling, recovery-token handling, enumeration resistance, and audit effects. WF-008 must provide the actual
typed API operations for authentication, recovery, logout, session list, and session revocation before client
routes or API calls are built.

The released v0.1.0 public surface supplies `ListActiveSessions`, `SessionView`, and `RevokeSession`.
The package rejects current-session revocation through that command. Its lifecycle-only
`SessionRevocationService::revokeAllActiveFor` does not authorize a bulk self-service control. See the
[released-package audit](../research/WF-001-released-package-contract-audit-research.md) for immutable evidence.
The root Composer dependencies still use development revisions; successful release adoption and actual Symfony
HTTP endpoints remain implementation acceptance gates, not claims made by this frontend direction.

### Required implementation evidence

- Behavior tests cover anonymous redirect and safe return handling, generic recovery acknowledgement, invalid or
  expired reset handling, theme default and persisted override, keyboard form behavior, session-revocation
  confirmation, current-session protection, and the intentionally empty Dashboard.
- Manually inspect responsive light and dark rendering, then run the repository's canonical `./bin/build`.
  Do not add tests that merely inspect Bootstrap configuration, CSS tokens, or tooling.

## Resolution

Open. Blocked by WF-005 and WF-008.
