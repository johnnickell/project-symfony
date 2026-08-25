---
id: T-00002
prd: PRD-00001
title: Establish the Full-Stack Symfony Web Foundation
status: done
blocked_by:
---

# Establish the Full-Stack Symfony Web Foundation

## Outcome

Replaced the composition-only bootstrap with a runnable Symfony web foundation using standard public and PHP
configuration seams, one rendered HTTP walking slice, and a repeatable local runtime command.

## Scope

- In scope: Runtime-backed front controller, PHP config, route config, HomeController, Twig template, Compose-managed Nginx+PHP-FPM
- Out of scope: login, persistence, database configuration, browser automation, release, visibility transitions

## Acceptance Criteria

- [x] The project has a Runtime-backed `public/index.php`, PHP package configuration, and PHP route configuration.
- [x] `GET /` renders the Hello World foundation page through a project-owned controller and Twig template.
- [x] `./bin/up` starts the Compose-managed PHP-FPM and Nginx services and exposes the application at
      `http://127.0.0.1:18083/`.
- [x] The full build proves the HTTP response alongside the existing architecture, planning, and production checks.

## Verification

Run `./bin/up`, `./bin/composer install`, `./bin/phpunit`, and `./bin/build`. The functional test asserts the
rendered home page without depending on a developer's host PHP version.

## Completion Notes

Verified 2026-08-18: `./bin/up` started the Compose `api` and `server` services, and `GET /` through Nginx at
`http://127.0.0.1:18083/` returned the rendered page. `./bin/planning-check` and `./bin/build` passed; the build
ran 5 tests with 56 assertions and completed its production autoload/kernel check.