# Ticket Board

Ticket files are canonical for status and blocking edges; this board is canonical for recommended execution order.
Update it whenever a ticket's status, dependencies, or roadmap priority changes.

## What’s Next? Contract

For an unqualified “what’s next?”, return the current decision under **Now** and the first executable item under
**Ready Frontier**. Do not select work by ID alone.

## Now

No local decision is pending. The next product capability is not yet planned.

## Ready Frontier

No executable local ticket is planned.

## Waiting

No ticket is currently waiting on an unfinished local dependency.

## Recently Done

| Ticket | Parent PRD | Outcome |
| --- | --- | --- |
| [T-00002 — Establish the Full-Stack Symfony Web Foundation](00002-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Added the Runtime-backed front controller, PHP configuration, Twig-rendered home page, and Compose-managed Nginx-to-PHP-FPM runtime without adding login or persistence. |
| [T-00001 — Establish the Canonical Symfony Starter Foundation](00001-TICKET.md) | [PRD-00001](../specs/00001-PRD.md) | Established canonical planning authority, Docker-backed Composer and PHPUnit commands, a `var/cache/<tool>` convention, scoped ignore rules, and a clean production-install check without implementing a product journey. |
