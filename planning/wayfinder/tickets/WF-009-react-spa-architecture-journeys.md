# WF-009 — React SPA Architecture and Journeys

**Labels:** `wayfinder:prototype`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-005, WF-008

## Question

Which React, TypeScript, ESBuild, routing, styling, and API-client structure—using the public Fight CMS client
layout as a structural baseline—gives the starter complete
editable human workflows while preserving the settled token and authorization boundaries?

## Must decide

- Prototype the source topology under `client/`, TypeScript boundaries, ESBuild entrypoints and chunks, Sass
  ownership, static assets, development workflow, and deterministic output under `public/dist/`.
- Define in-memory access-token ownership, startup refresh, single-flight automatic refresh, request replay limits,
  logout, expiry, tab behavior, and navigation after authentication-state changes; never persist bearer tokens in
  browser storage.
- Define routing, authenticated and permission-aware layouts, loading/error/empty states, form validation,
  accessibility, responsive behavior, and server fallback for client-side routes.
- Enumerate human journeys for login, invitation/activation, account and credential management, sessions, users,
  roles, permissions, grants, policy administration, and the human-relevant part of agent administration.
- Define generated or typed API-client authority, OpenAPI drift checks, realtime subscription/refetch behavior,
  and test seams.
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

## Resolution

Open. Blocked by WF-005 and WF-008.
