# Fight Symfony Starter

Public Symfony starter for Fight Common and Fight AccessControl. Source visibility does not imply a release,
Packagist publication, template enablement, or create-project distribution.

## Local development

```sh
./bin/up
./bin/composer install
./bin/phpunit
./bin/build
```

The application is available at http://127.0.0.1:18083/ while the Compose stack is running. `./bin/build` is the
noninteractive completion gate used by GitHub Actions. `planning/CONVENTIONS.md` defines the planning structure,
`planning/README.md` names the local planning authority, while `planning/tickets/BOARD.md` gives the current
human decision and TASK execution order. New work follows EPIC → TICKET → TASK; preserved legacy records retain
their original authority. General lifecycle cutover remains guarded by independent acceptance of TASK-00001;
see the control in `planning/README.md`.

`./bin/up` pulls the pinned PHP build base, builds the services, and recreates containers in the background.
It returns nonzero on preparation/start failure; use `./bin/up --logs` to follow service logs after startup,
and `./bin/down` to stop the selected Compose stack.

### PHP security rebuild

FPM and the CLI build use PHP **8.5.12 Bookworm**, pinned by exact tag and immutable multi-platform manifest.
This fixes CVE-2026-109618 and CVE-2026-109619. The separate
[PHP October 23 precaution](https://www.php.net/archive/2026.php#2026-10-23-1),
`session.upload_progress.enabled=0`, is loaded from INI before request parsing; it is not a claim that 8.5.12
fixes every session-related issue. The FPM image includes this INI and Compose mounts the checkout's copy.

Before rebuilding, inspect `docker compose config` and `docker compose ps -a`: verify the project name, source
mounts, images and port 18083 belong to this checkout. In a worktree, set a unique `COMPOSE_PROJECT_NAME` and
use that same value for every wrapper, including shutdown. If another project owns the port, stop here; do not
stop or replace its services. Concurrent port/topology isolation remains separate work.

```sh
# With the intended checkout/project selected and its HTTP port free:
./bin/down                         # Stops only that project; does not remove volumes
./bin/up                           # Pulls pinned bases, builds, recreates all FPM workers
./bin/exec php -v
./bin/exec php-fpm -i | grep -E 'PHP Version|session.upload_progress.enabled'
./bin/composer install --no-interaction --prefer-dist --no-progress
./bin/composer check-platform-reqs  # Checks real PHP, not Composer's simulated platform
./bin/build
```

Require actual FPM/CLI PHP 8.5.12 and `session.upload_progress.enabled => Off => Off` in the FPM output;
inspect `docker compose images` for the running image identity. A container restart alone does not adopt a new
image. Do not serve requests using an old container while preparing its replacement. Unavailable pinned bases
must fail preparation: restore registry access rather than removing the digest, skipping the rebuild or falling
back to cached floating tags. Composer requires PHP >=8.5.12 and simulates 8.5.12 for dependency resolution;
that metadata alone is not runtime proof.

`./bin/build` always builds with `--pull`, tags the image by a hash of the canonical checkout path, and runs the
resulting immutable image ID. It does not replace the old shared `project-symfony-build:latest` tag or recreate
FPM; use `./bin/up` for that. A failed base resolution is fatal, not permission to use an older image. Later PHP
security updates require a deliberate tag/digest and Composer-platform change, not an update-on-start mechanism.

## Quality gate

Fight Common is consumed through its released `^1.2` Composer constraint. `./bin/build` validates planning and
Composer metadata, installs dependencies, checks PHP syntax, runs the installed `FightCommon` PHPCS standard,
PHPStan, Deptrac, Rector, exact Unit coverage, and the retained Integration and Functional journeys.

The Symfony adapter boundary lives under `src/Adapter/`. `public/index.php` explicitly composes the canonical
Fight Common JSON middleware around `App\Adapter\Kernel`.
Shared providers are registered by capability under `config/common/`; messaging and templating compiler-pass proof
is test-owned, and production has no event-sourcing infrastructure.
