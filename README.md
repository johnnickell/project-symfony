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
execution order.

`./bin/up` starts the services in the background and returns. Use `./bin/up --logs` to follow service logs, and
`./bin/down` to stop the Compose stack.

## Fight Common candidate validation

Fight Common is temporarily consumed from its immutable pre-release commit. `./bin/build` runs ordinary Composer
validation through `scripts/validate-composer-candidate.php`: it permits only Composer's unavoidable warning about
that exact commit reference and fails for validation errors or any additional warning. Remove this temporary
allowlist when Fight Common 1.2 receives its release tag.

The committed `composer-lowest.lock` and digest are the reproducible lowest graph; the root `composer.lock` is
the latest graph. `./bin/build` installs and boots both in isolated roots, verifies neither lock drifts, and asks
Fight Common's `StarterSupportReceiptAuthority` from an exact disposable candidate checkout to validate the
data-only receipt. Run `scripts/verify-dependency-lanes.sh refresh-lowest` inside the build image only when the
lowest evidence is intentionally refreshed.

The Symfony adapter boundary lives under `src/Adapter/`. `public/index.php` explicitly composes the canonical
Fight Common JSON middleware around `App\Adapter\Kernel`, while console and production checks boot the bare Kernel.
Shared providers are registered by capability under `config/common/`; messaging and templating compiler-pass proof
is test-owned, and production has no event-sourcing infrastructure.
