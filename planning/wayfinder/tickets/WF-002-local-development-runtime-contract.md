# WF-002 — Local Development Runtime Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Closed
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

This ticket settles local topology and operator behavior only. Runtime implementation remains in later vertical
T- tickets; no service, configuration, image, key, volume, or script is created while charting.

## Resolution

Closed on 2026-09-12. John accepted the runtime direction: one HTTPS origin per worktree, loopback-only exposure,
isolated MySQL/Redis volumes, explicit readiness, workers plus Cron scheduling, local mail capture, and
data-preserving shutdown. The contract below is canonical; the
[research record](../research/WF-002-local-runtime-contract-research.md) supplies feasibility and release evidence.
WF-003 through WF-008 retain HTTP, persistence, security, authorization, and delivery semantics in this same map.

## Accepted topology

| Service | Responsibility | Exposure and storage |
|---|---|---|
| `server` | Nginx TLS edge, static assets, FastCGI, same-origin Mercure subscription proxy | One loopback HTTPS port; read-only source/assets/certificate mounts |
| `api` | PHP 8.5 FPM, Symfony HTTP composition | Internal FastCGI only; writable project cache/log paths; UID/GID 1000 |
| `cli` | Same PHP extensions and application configuration for one-shot Composer, console, migration, and maintenance | No daemon or published port; explicit command failures propagate |
| `worker` | PHP 8.5 CLI Messenger consumption | No host port; instance-unique consumer identity, graceful shutdown |
| `scheduler` | Cron-triggered bounded scheduling tick; work dispatched for worker consumption | UTC, no host port; exact due-work and overlap contract belongs to WF-007 |
| `database` | MySQL 8.4 application and audit persistence | Internal 3306 only; project-scoped named volume |
| `redis` | Redis streams, locks, and bounded transient coordination | Internal 6379 only; project-scoped volume with AOF; never source of account authority |
| `mercure` | Private invalidation subscriptions and authenticated publisher endpoint | Internal endpoint; browser subscriptions through `server`; anonymous access/debugger disabled |
| `assets` | Node LTS install/build/watch using locked npm dependencies and ESBuild | Writes `public/dist/`; no separate browser-facing dev origin |
| `mailpit` | Local-only SMTP capture for acceptance and operator inspection | Internal SMTP; mailbox UI proxied under the same dev-only gate as other diagnostics |

### Image selection and pinning

Use PHP 8.5 CLI/FPM on Bookworm, MySQL 8.4 LTS, Nginx 1.30 stable, Redis 8.2 on Bookworm,
Mercure Community 0.24, Node 24 LTS, and Mailpit 1.31. The scheduler and worker reuse the PHP CLI image.
These are selected compatibility lines, not a claim that images have been built or certified together.

At implementation, resolve the newest non-prerelease patch within each selected line, verify amd64/arm64
manifests and the required health/extension behavior, and commit exact patch tags plus immutable manifest digests.
Do not use floating `latest`, alpha Mercure, or runtime update-on-start. An unavailable or unsupported line requires
an explicit planning correction; do not silently advance a major/minor version. Capturing digests and proving the
selected images are implementation evidence, not another architecture decision. See the linked research for the
upstream release evidence behind these choices.

## Environment and isolation contract

- Derive the default project name as `symfony-` plus the first 12 lowercase hexadecimal characters of SHA-256 of
  the canonical absolute worktree path. A caller may override `COMPOSE_PROJECT_NAME`; all wrappers must resolve
  and report the same value. Reject an existing project name owned by a different worktree. Never set globally
  shared container or volume names; scope development/build image tags and transient build resources as well.
- Use `<project-name>.localhost` as the browser hostname, with primary HTTPS port 18083 and an explicit
  `HTTPS_PORT` override for concurrent worktrees. Startup checks port ownership and fails clearly on collision;
  it does not stop another project or silently select a new origin. Distinct hostnames isolate cookies as well
  as ports; port-only isolation is insufficient for cookies.
- `APP_ORIGIN` is the exact HTTPS browser origin. Internal service URLs use service DNS names, including separate
  Mercure publisher and browser subscription URLs. Do not compile server secrets into frontend assets.
- `.env.example` describes `APP_ENV`, `APP_DEBUG`, `APP_ORIGIN`, `HTTPS_PORT`, `COMPOSE_PROJECT_NAME`, database
  connection/user/database names, Redis DSN, mail capture DSN, internal/browser Mercure URLs, and paths to
  app secret, JWT keys/key ID, delivery-encryption key, hub publisher/subscriber keys, and TLS certificate/key.
  Keep safe local names/defaults in the example, with empty required secrets and no production credentials.
- First setup generates project-local development keys into ignored storage with restricted permissions. It
  never overwrites existing keys on `up`. Browser trust installation is an explicit documented operator step;
  no wrapper silently modifies host trust stores. Automated browser acceptance trusts the test certificate only.
- Production-like cookie guarantees remain in local development: TLS supports Secure cookies without a local
  insecure-cookie fallback. WF-005 owns exact cookie/CSRF behavior.
- Bind mounts always resolve from the selected worktree. Dependencies, assets, and caches cannot come from a
  sibling checkout. Named database, Redis, and hub volumes are scoped by the resolved Compose project.
- Preserve non-root FPM UID/GID 1000. One-shot dependency/build jobs may use the host UID/GID for bind-mounted
  output. Set writable paths deliberately; never recursively chown the source checkout as startup behavior.

## Readiness and operator contract

1. `./bin/up` validates origin, ports, secrets, and configuration; builds images; starts storage/hub/mail services;
   requires health; runs dependency/asset preparation and migration one-shots; then starts API, workers,
   scheduler, and edge. Bootstrap administration is a separate explicit invitation-led command.
2. Poll readiness every five seconds with a three-second probe timeout and a 120-second bound per service-start
   phase. Image/dependency preparation is separately reported and may exceed that bound on a clean clone.
   MySQL must accept the application user's query; Redis must answer authenticated PING; FPM must answer its
   internal ping; the hub must answer its documented readiness probe; the edge must answer an application health
   request after migrations. Worker/scheduler readiness requires an advancing heartbeat within 90 seconds.
3. A failure returns nonzero with the failing service/phase and an actionable log command. Startup must never print
   success because containers merely exist. Downstream outages make readiness fail; they do not trigger schema
   reset, key regeneration, or an unbounded bootstrap retry.
4. Long-running services restart `unless-stopped`; one-shots do not restart automatically. Give workers a
   30-second graceful stop budget. WF-007 owns delivery acknowledgements and recovery after forced termination.
5. `./bin/up --logs` enters log follow only after readiness. Normal `up`, `exec`, and `build` remain noninteractive.
   `./bin/exec` keeps its established PHP command meaning; service-specific maintenance uses documented wrappers
   or explicit Compose service selection without changing that compatibility contract.
6. `./bin/down` stops only the resolved project and preserves named volumes, keys, and source. Configuration changes
   requiring replacement use `down` followed by `up`. Destructive volume removal is a separately requested command
   with a named project and visible data-loss boundary; it is never part of build, ordinary shutdown, or shipping.
7. Swagger UI, mailbox UI, and diagnostic routes are denied outside `APP_ENV=dev` even if their paths are known.
   No database, Redis, hub publishing, or debug port is bound to all host interfaces.

## Required later implementation evidence

Run the real wrappers for a clean clone and two simultaneous worktrees. Show independent browser hostnames,
ports, database contents, Redis streams, assets, cookies, and volumes. Demonstrate readiness failure for broken
credentials, storage/hub outage, failed migration, occupied port, and stale worker heartbeat. Show ordinary
shutdown/restart preserves data and that deliberate volume deletion identifies only the requested project.

Tests belong on production code and real boundaries; wrapper/configuration behavior is verified through these
commands and human inspection. This decision introduces no source, service, environment, key, or container change
and makes no deployment, production durability, availability, or release claim.
