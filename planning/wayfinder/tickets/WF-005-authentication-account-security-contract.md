# WF-005 — Authentication and Account Security Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** WF-003, WF-004

## Question

What complete browser and API authentication contract should Symfony Security enforce for human account
lifecycle workflows without weakening the released AccessControl semantics?

## Must decide

- Define asymmetric JWT issuance and validation, claims, key identifiers and rotation, clocks, issuer/audience,
  access-token lifetime, and authoritative user/session revalidation.
- Define Symfony Security firewalls, authenticators, user loading, stateless/stateful boundaries, entry points,
  denial responses, and trusted-proxy handling.
- Define refresh-cookie issuance, host/path/same-site/security attributes, single-use rotation, concurrent reuse,
  family revocation, expiry, and audit behavior.
- Define exact method, content type, CSRF/origin and fetch-metadata policy for refresh, logout, and other
  cookie-authenticated mutations, plus the allowed CORS boundary.
- Define login, logout, invitation activation, account activation, password reset, email-change request and
  confirmation, credential change, and session-revocation semantics, including enumeration resistance.
- Define delivery-token lifetime, one-time use, replay, throttling, generic responses, and safe failure handling.
- Keep short-lived access tokens in client memory; issue refresh credentials only in Secure, HttpOnly cookies;
  rotate on use, detect reuse, and fail closed on replay, revocation, expiry, or concurrent rotation loss.
- Rate-limit authentication and recovery by appropriate account/source dimensions and forbid secrets, bearer tokens,
  refresh credentials, reset/activation credentials, or raw request material in logs and diagnostics.

## Resolution boundary

This ticket settles security behavior and adapter responsibilities. It may not implement firewalls, controllers,
cookies, mail, keys, or persistence; assign fine-grained resource permissions; or finalize the endpoint matrix.

## Resolution

Open. Blocked by WF-003 and WF-004.
