# WF-006 — Principal and Authorization Mapping

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-001, WF-004, WF-005

## Question

How do released User, Role, Permission, Session, Policy, and Agent authorization capabilities map to authenticated
human and machine principals in Symfony?

## Must decide

- Define distinct authenticated User and Agent principal shapes, credentials, token purposes, lifecycle states,
  session relationships, and denial behavior.
- Map roles, permissions, direct and inherited grants, managed policy, resource context, and package authorization
  decisions to Symfony voters or authorization services without duplicating policy logic.
- Define administrator, self-service, support/operator, and machine-agent authority boundaries, including who may
  create, inspect, modify, revoke, or impersonate each principal type.
- Define tenant or scope context if exposed by the released contracts, default-deny behavior, cache invalidation,
  revocation visibility, and audit requirements.
- Define route-level versus application-operation enforcement so controllers cannot become the sole security
  boundary and workers cannot bypass authorization rules.

## Resolution boundary

This ticket may settle principal translation and authorization composition. It may not invent package permissions,
implement voters or endpoints, require UI for machine/operator workflows, or reopen the authentication mechanics
owned by WF-005.

## Resolution

Open. Blocked by WF-001, WF-004, and WF-005.
