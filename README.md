# Fight Symfony Starter

Current delivery actions use the [Board's explicit handoffs](planning/tickets/BOARD.md#current-delivery-handoffs),
not historical PR/acceptance checkpoints. [Delivery conventions](planning/CONVENTIONS.md#current-delivery-handoffs)
keep ordinary checks offline and bound live checking/authorized intake retirement separately.

Public Symfony starter for Fight Common and Fight AccessControl. Source visibility does not imply a release,
Packagist publication, template enablement, or create-project distribution.

## Local development

```sh
./bin/prepare                      # Explicit startup, locked install and readiness checks
./bin/phpunit --testsuite Unit     # Focused iteration, not the complete gate
./bin/build
```

The application is available at http://127.0.0.1:18083/ while the Compose stack is running. `./bin/build` is the
noninteractive completion gate used by GitHub Actions. `planning/CONVENTIONS.md` defines the planning structure,
`planning/README.md` names the local planning authority, while `planning/tickets/BOARD.md` gives the current
human decision and TASK execution order. New work follows EPIC → TICKET → TASK; preserved legacy records retain
their original authority. The planning lifecycle is active; a future replacement for tracked planning is not
implemented. Read [Architecture](ARCHITECTURE.md), [Foundation](planning/FOUNDATION.md), and
[engineering guidance](docs/engineering/STANDARDS.md). Shared Pi skills use the local
[project context](docs/engineering/PROJECT_CONTEXT.md), with no copied suite or required Jev enrollment.

Prerequisites: Docker with Compose v2 (`up --wait` support), Bash, host Python 3.9+ and Git; no host PHP or Composer.
On native Linux, first select your non-root `APP_UID`/`APP_GID` as described in
[Build guidance](docs/engineering/BUILD.md#preparation-and-isolation) so container writes match the checkout owner.
`./bin/prepare` is explicit and noninteractive: it runs `./bin/up`, installs the existing Composer lock, then runs
`./bin/doctor` to check actual CLI/FPM, extensions, Xdebug coverage mode and Composer platform requirements.
No dependency update or runtime fallback is performed. `./bin/up` pulls the pinned PHP build base, builds the two
services and recreates them in the background, waiting for FPM process health. Use `./bin/up --logs` to follow logs,
`./bin/doctor` to inspect an already-prepared service without installing anything, and `./bin/down` to stop only the
selected stack, preserving volumes. All preparation steps propagate failure. See
[Build guidance](docs/engineering/BUILD.md#preparation-and-isolation) for worktrees, resources and diagnosis.

### PHP security rebuild

FPM and the CLI build use PHP **8.5.12 Bookworm**, pinned by exact tag and immutable multi-platform manifest.
This fixes CVE-2026-109618 and CVE-2026-109619. The separate
[PHP October 23 precaution](https://www.php.net/archive/2026.php#2026-10-23-1),
`session.upload_progress.enabled=0`, is loaded from INI before request parsing; it is not a claim that 8.5.12
fixes every session-related issue. The FPM image includes this INI and Compose mounts the checkout's copy.

Before rebuilding, inspect `docker compose config` and `docker compose ps -a`: verify the project name, source
mounts, images and port 18083 belong to this checkout. In a worktree, set a unique `COMPOSE_PROJECT_NAME` and
use that same value for every wrapper, including shutdown. Set a free `HTTP_PORT` (default 18083) for concurrent
preparation. The wrappers refuse foreign project mounts, unknown network ownership and occupied HTTP ports before
startup mutation. Do not bypass that refusal or stop another checkout's services. This is minimal preparation
isolation, not the full WF-002 topology.

```sh
# With the intended checkout/project selected and its HTTP port free:
./bin/down                         # Stops only that project; does not remove volumes
./bin/up                           # Pulls pinned bases, builds, recreates all FPM workers
./bin/exec php -v
./bin/exec php-fpm -i | grep -E 'PHP Version|session.upload_progress.enabled'
./bin/composer install --no-interaction --prefer-dist --no-progress
./bin/doctor                       # Checks real CLI/FPM, extensions, coverage mode and platform
./bin/build
```

Require actual FPM/CLI PHP 8.5.12 and `session.upload_progress.enabled => Off => Off` in the FPM output;
inspect `docker compose images` for the running image identity. A container restart alone does not adopt a new
image. Do not serve requests using an old container while preparing its replacement. Unavailable pinned bases
must fail preparation: restore registry access rather than removing the digest, skipping the rebuild or falling
back to cached floating tags. Composer requires PHP >=8.5.12 and simulates 8.5.12 for dependency resolution;
that metadata alone is not runtime proof.

`./bin/build` uses the already-running, checkout-owned `api` service; it never builds/pulls images or installs
packages. Use explicit preparation to adopt the pinned image and locked dependencies. A failed base resolution
is fatal, not permission to use an older image. Later PHP security updates require a deliberate tag/digest and
Composer-platform change, not an update-on-start mechanism.

### HttpFoundation security baseline

The root `symfony/http-foundation` constraint is `^8.1.9`; the lock selects 8.1.9. This includes the fix for
CVE-2026-108082 and the subsequent whitespace-separator correction. For future authorized updates, retain this
minimum, select the latest compatible stable release, and verify locked/installed identity and
`./bin/composer audit --locked`. Do not suppress the advisory. Audit results are point-in-time dependency evidence,
not a deployment or globally clean security claim; no ongoing security gate is introduced here.

## Quality gate

Fight Common and Fight AccessControl use released `^1.4` and `^0.8` Composer constraints, respectively.
Installing AccessControl does not expose its capabilities as application endpoints. After explicit preparation,
`./bin/build` validates planning on the host, then runs named, fail-fast PHP phases in Compose `api`: runtime/locked
dependency readiness, strict Composer validation and platform requirements, PHP syntax, the installed `FightCommon`
PHPCS standard, PHPStan, both Deptrac checks, Rector dry-run, exact Unit coverage, and Integration and Functional
journeys. Each suite runs once; Unit evidence remains at `var/reports/coverage/unit/clover.xml` after the gate.
Hosted CI explicitly prepares and uses this same gate against the source head, not a parallel pipeline.

The Symfony adapter boundary lives under `src/Adapter/`. `public/index.php` explicitly composes the canonical
Fight Common JSON middleware around `App\Adapter\Kernel`.
Shared providers are registered by capability under `config/common/`; messaging and templating compiler-pass proof
is test-owned, and production has no event-sourcing infrastructure. [Build guidance](docs/engineering/BUILD.md)
documents preparation, phases, durable Unit evidence and failure diagnosis; [Scratch](docs/engineering/SCRATCH.md) owns categorized
ignored evidence and reversible legacy relocation. Independent [Review](docs/engineering/REVIEW.md) and
[QA](docs/engineering/QA.md) are distinct from a green build and human merge.
