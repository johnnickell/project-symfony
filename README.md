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

`./bin/up` starts the services in the background and returns. Use `./bin/up --logs` to follow service logs, and
`./bin/down` to stop the Compose stack.

## Quality gate

Fight Common is consumed through its released `^1.2` Composer constraint. `./bin/build` validates planning and
Composer metadata, installs dependencies, checks PHP syntax, runs the installed `FightCommon` PHPCS standard,
PHPStan, Deptrac, Rector, exact Unit coverage, and the retained Integration and Functional journeys.

The Symfony adapter boundary lives under `src/Adapter/`. `public/index.php` explicitly composes the canonical
Fight Common JSON middleware around `App\Adapter\Kernel`.
Shared providers are registered by capability under `config/common/`; messaging and templating compiler-pass proof
is test-owned, and production has no event-sourcing infrastructure.
