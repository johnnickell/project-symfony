# Symfony architecture rules

Fight packages provide public contracts. This project provides Symfony-native composition and adapters.

- Keep Symfony HTTP, security, persistence, configuration, and presentation types in project-owned adapters.
- Controllers and framework entry points translate requests and responses; they do not recreate shared Domain or
  Application behavior.
- Register services, aliases, autoconfiguration, and compiler passes through `config/services.php` and the
  project Kernel. Do not add a Fight bundle.
- New persistence schemas, migrations, records, mappings, repositories, fixtures, and presentation models belong
  to this repository and require a local vertical ticket.
