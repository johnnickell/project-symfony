# WF-002 — Local Development Runtime Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Frontier:** Current
**Gate:** —
**Map:** [Symfony AccessControl Starter Application](../symfony-access-control-application-map.md)
**Depends on:** —

## Question

What local-development runtime contract makes the complete starter understandable, healthy, persistent where
intended, and safe to run concurrently from isolated worktrees?

## Must decide

- Define the Compose services and responsibilities for Nginx, PHP-FPM, PHP-CLI workers, MySQL, Redis, Mercure,
  Cron-triggered scheduling, frontend asset development, and one-shot maintenance commands.
- Define the `.env.example` contract, safe development defaults, secret placeholders, generated-key handling, and
  which values are shared by containers, browser tooling, and host-facing documentation.
- Define startup ordering, health checks, readiness criteria, restart behavior, and the operator-visible failure
  modes for each long-running service.
- Define host ports, internal networks, bind mounts, named volumes, database and Redis persistence, source and asset
  mounts, and ownership/permission behavior.
- Define deterministic Compose project naming, per-worktree port and volume isolation, clean-clone bootstrap,
  `./bin/up`, `./bin/down`, `./bin/exec`, and safe cleanup semantics.
- Separate local-development certification from every production deployment or availability claim.
- Keep Swagger UI and every operational dashboard local-only by default, with startup or request-time denial outside
  development unless a later explicit authorization contract exists.

## Resolution boundary

This ticket may settle the local runtime topology and operator contract without knowing the package API surface.
It may not add services, environment variables, images, volumes, runtime code, or scripts. It does not select
production infrastructure or certify deployment.

## Resolution

Open. This is the current Wayfinder frontier.
