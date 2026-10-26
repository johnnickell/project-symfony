# Foundation and capability inventory

Fight Symfony Starter is a public-source, Symfony-native application foundation. It composes public Fight Common
and Fight AccessControl contracts without becoming their implementation home. Source visibility does not imply a
release, Packagist publication, template enablement or create-project distribution.

| Boundary | Current implementation | Remaining scope |
| --- | --- | --- |
| Composition | `src/Adapter/Kernel.php`, `config/services.php`, capability modules in `config/common/` | New bindings require the installed public contract and accepted use case |
| HTTP | Twig-rendered Hello World homepage through Nginx/PHP-FPM; explicit front-controller JSON middleware | No production login, application API or browser journey |
| Console | Bare-Kernel entry through `scripts/console.php` and `bin/console` | No application command workflows implied |
| Dependencies | Released Common v1.4.0 and AccessControl v0.8.0 through Composer; no AccessControl use cases are wired | WF-001 still owns the full released-contract/schema audit before application feature implementation |
| Verification | Prepared Compose `api` gate with named PHP phases, host planning, durable exact Unit coverage and retained Integration/Functional suites | TASK-00006 is independently accepted with applicable QA passed and published for human review/merge as [PR #23](https://github.com/johnnickell/project-symfony/pull/23) |
| Planning | Active EPIC → TICKET → TASK lifecycle with preserved legacy records | A future replacement for tracked planning is not implemented |
| Shared skills | External provider resources with local [project context](../docs/engineering/PROJECT_CONTEXT.md) | Installation and actual runtime qualification remain operator-owned |

[Architecture](../ARCHITECTURE.md) owns boundaries. The [Board](tickets/BOARD.md) owns executable priority,
[Roadmap](ROADMAP.md) owns strategy, and [Wayfinder](wayfinder/README.md) owns unresolved decisions.
The publication-stage wording above is a historical checkpoint: [current Board reconciliation](tickets/BOARD.md#now)
records PR #23 merged/integrated, with no remaining merge action. Historical receipts are not fresh proof. This foundation is not a persistence, authentication, release or
public-readiness claim.
