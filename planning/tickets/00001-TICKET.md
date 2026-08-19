---
id: T-00001
prd: PRD-00001
title: Establish the Canonical Symfony Starter Foundation
status: done
blocked_by: []
---

# Establish the Canonical Symfony Starter Foundation

## What to build

Establish the reusable Symfony-starter baseline: native composition through public Fight packages, a canonical
local planning surface, Docker-backed developer commands, and a clean-clone build without implementing a product
journey.

## Acceptance criteria

- [x] `planning/specs/00001-PRD.md` is the locally numbered PRD authority and `planning/tickets/BOARD.md` is the
      operational execution board.
- [x] `./bin/composer install` creates the Composer vendor directory through the project image.
- [x] `./bin/phpunit`, `./bin/console`, `./bin/planning-check`, and `./bin/build` each have a documented,
      repository-owned role.
- [x] Local generated artifacts, test caches, analysis caches, Composer credentials, and editor state are ignored
      without ignoring lockfiles or source configuration.
- [x] The foundation continues to consume Fight Common and Fight AccessControl only as Composer packages and does
      not implement login, persistence, browser journeys, releases, or visibility transitions.

## Verification

Run `./bin/composer install`, `./bin/phpunit`, `./bin/planning-check`, and `./bin/build` from a clean checkout.
Review the full diff for copied shared source, credentials, generated vendor files, and untracked build output.

Verified 2026-08-18: `./bin/composer install`, `./bin/planning-check`, `./bin/phpunit` (4 tests, 44 assertions),
and `./bin/build` passed. The build leaves the development vendor directory intact while testing the production
installation in an isolated temporary copy.

## Documentation impact

Update the root README, contribution guide, agent guidance, PRD, ticket board, and planning authority map together.

## Explicit exclusions

No login, database, browser, release, publishing, template, or public-visibility work is authorized by this ticket.
