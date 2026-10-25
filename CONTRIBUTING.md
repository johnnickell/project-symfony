# Contributing

Read [AGENTS.md](AGENTS.md), [Architecture](ARCHITECTURE.md), [Foundation](planning/FOUNDATION.md),
[planning conventions](planning/CONVENTIONS.md) and [engineering standards](docs/engineering/STANDARDS.md).
New work follows accepted EPIC → TICKET → TASK scope; preserved legacy records keep their existing authority.
Select the checkout/worktree and authorize execution explicitly. Use feature branches from `develop`, never
commit directly to `develop`/`main`, and preserve unrelated changes.

## Development and verification

Use the repository's Docker wrappers:

```sh
./bin/prepare
./bin/doctor
./bin/phpunit --testsuite Unit
./bin/build
```

[Build guidance](docs/engineering/BUILD.md) describes preparation, current gate mechanics, exact Unit-only coverage,
artifact limitations and the detached long-build protocol. Focused checks do not replace the full gate. Preserve
required hosted verification separately from local results. Runtime/cache files belong under `var/`, including
`var/cache/<tool>`; [execution scratch](docs/engineering/SCRATCH.md) uses categorized ignored `.runs/` directories.

## Ownership and delivery

Do not duplicate Fight Common or Fight AccessControl behavior. Public shared changes belong in the package;
Symfony composition and adapters belong here. Keep current source organization, safe transport boundaries and
meaningful behavior tests. Use Action `handle()` and Responder `respond()` for authorized presentation changes;
no invented use case is needed for the HTML homepage.

Follow [Execution](docs/engineering/EXECUTION.md), independent [Review](docs/engineering/REVIEW.md), applicable
[QA](docs/engineering/QA.md), and authorized [Landing](docs/engineering/LANDING.md). The implementer cannot accept
their own work. Reports and detailed acceptance evidence remain ignored, not new tracked planning narratives.
Keep pending status truthful; publication and human merge remain separate.

For shared Pi workflows use [project context](docs/engineering/PROJECT_CONTEXT.md), not copied skills. Native
`/skill:next` recommends only. Explicit `/skill:build` delegates delivery stages; `./bin/build` only verifies.
No installation, enrollment, release, database planning cutover or archive follows automatically.
