# Project context

Fight Symfony Starter is a public-source, Symfony-native application foundation. It composes public Fight Common and
Fight AccessControl contracts without becoming their implementation home.

The composition root is project-owned: `src/Adapter/Kernel.php`, `config/services.php`, and the capability-scoped
files under `config/common/`. Framework adapters belong under `src/Adapter/` and must keep framework request,
security, persistence, and presentation details outside Fight package Domain and Application contracts. The web
front controller explicitly wraps the bare Kernel with Fight Common's canonical Symfony JSON middleware; console
and production boot checks use the bare Kernel.

The current bootstrap proves only a rendered Hello World HTTP slice through Nginx and PHP-FPM. It is deliberately
not a login, database, browser journey, release, or public-readiness claim.
