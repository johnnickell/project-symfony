# AGENTS.md

Repository-local instructions are canonical for implementation, planning, triage, and completion. The planning
authority map is `planning/README.md`; detailed tickets and execution order live in `planning/tickets/`.

Read `CONTEXT.md` and the focused instructions in `planning/agents/` before changing behavior. Work in small,
independently verifiable vertical slices. A slice is complete only when its local ticket acceptance, architecture
checks, documentation, and `./bin/build` are green.

Use repository-owned commands: `./bin/composer`, `./bin/phpunit`, `./bin/console`,
`./bin/planning-check`, and `./bin/build`. Do not add wrappers for tooling that the current Composer manifest does
not provide; add the dependency, its configuration, and its build lane together in an authorized local ticket.

## Architecture boundary

`johnnickell/fight-common` and `johnnickell/fight-access-control` are consumed only as Composer packages.
Never copy their Domain or Application source, reach into package internals; there is no Fight bundle. Symfony owns
its namespace loading, autoconfiguration, compiler passes, aliases, environment configuration, HTTP/security
composition, and every persistence or presentation adapter.

Do not implement login, persistence, browser journeys, releases, publishing, or visibility transitions unless a
local ticket explicitly authorizes that work.
