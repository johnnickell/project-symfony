# Project context

Fight Symfony Starter is a public-source, Symfony-native application foundation. It composes public Fight Common and
Fight AccessControl contracts without becoming their implementation home.

The composition root is project-owned: `src/Kernel.php`, `config/services.php`, and
`src/Composition/`. Framework adapters belong in this repository under `src/` and must keep framework request,
security, persistence, and presentation details outside Fight package Domain and Application contracts.

The current bootstrap proves only a rendered Hello World HTTP slice through Nginx and PHP-FPM. It is deliberately
not a login, database, browser journey, release, or public-readiness claim.
