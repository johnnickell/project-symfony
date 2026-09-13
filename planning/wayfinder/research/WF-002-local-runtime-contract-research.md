# WF-002 — Local Runtime Contract Evidence

Prepared 2026-09-12 for
[Local Development Runtime Contract](../tickets/WF-002-local-development-runtime-contract.md).
The accepted contract lives only in WF-002. John approved its runtime direction on 2026-09-12; no runtime
changes have been made.

## Current evidence

At starter base `dfbbc7b291b94fe70c25c5c47994a99bb392125a`, `compose.yaml` contains only PHP-FPM `api` and
Nginx `server`, a source bind mount, and host HTTP port 18083. `./bin/up` builds then starts Compose;
`./bin/down` retains volumes; `./bin/exec` executes noninteractively in `api`. FPM runs as UID/GID 1000.
The canonical build uses an isolated PHP 8.5 CLI image; this existing baseline does not certify the proposed
database, workers, scheduler, hub, TLS, or frontend environment.

Docker distinguishes a started container from a ready dependency and supports `service_healthy` and
`service_completed_successfully` conditions. Project names isolate Compose resources, but host ports and bind
mounts require separate handling. Sources:
[startup order](https://docs.docker.com/compose/how-tos/startup-order/),
[project names](https://docs.docker.com/compose/how-tos/project-name/).

Symfony documents Redis streams and consumer identities in
[Messenger](https://symfony.com/doc/current/messenger.html), schedules consumed by workers in
[Scheduler](https://symfony.com/doc/current/scheduler.html), and private hub integration in
[Mercure](https://symfony.com/doc/current/mercure.html). The
[hub installation guide](https://mercure.rocks/docs/hub/install) warns that its development configuration enables
anonymous subscription and its debugger; the starter must configure those explicitly rather than inherit them.
These are feasibility references, not a running prototype or durability proof.

## Upstream image-line evidence

- The existing canonical build reports PHP 8.5.10; PHP CLI/FPM remain aligned on 8.5 Bookworm.
- [MySQL 8.4 reference](https://dev.mysql.com/doc/refman/8.4/en/) supplies the selected LTS database documentation.
- [Nginx official images](https://hub.docker.com/_/nginx) list the stable 1.30 line.
- [Redis official images](https://hub.docker.com/_/redis) list the supported 8.2 Bookworm line.
- [Mercure releases](https://github.com/dunglas/mercure/releases) identify the stable 0.24 line separately from
  the 1.0 alpha series.
- [Node release schedule](https://nodejs.org/en/about/previous-releases) identifies Node 24 as LTS.
- [Mailpit releases](https://github.com/axllent/mailpit/releases) identify the 1.31 line.

These sources were read during this planning session. Exact patch tags, immutable image manifests, supported
architectures, extension compatibility, and image health probes must be verified by the implementation gate;
no image-pull or proposed-runtime acceptance claim is made here. The rule for selecting and pinning them is in
WF-002, not this research file.
