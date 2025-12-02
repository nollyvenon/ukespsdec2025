<!--
This file is intended to orient AI coding agents (Copilot-style assistants)
so they can be immediately productive in this repository.
Keep this file concise and strictly tied to discoverable repository patterns.
-->

# Copilot / AI Agent Notes — ukesps

Purpose: give an AI code assistant the minimal, actionable knowledge it needs
to make safe, correct edits in this Laravel project.

Quick commands (use exact scripts from `composer.json`):
- Setup (fresh machine): `composer run setup`
- Dev (local, starts server, queue listener, pail and vite): `composer run dev`
- Run tests: `composer run test` (runs `php artisan test`)
- Frontend build: `npm run build` or `npm run dev`

Environment and runtime:
- PHP requirement: ^8.2 (see `composer.json`).
- Framework: Laravel (project skeleton + `artisan` CLI present).
- Frontend: Vite + Tailwind (`vite.config.js`, `tailwind.config.js`, `resources/`).

High-level architecture (what to know):
- Standard Laravel MVC: controllers under `app/Http/Controllers`, views in
  `resources/views`, routes in `routes/` (see `routes/web.php`, `routes/auth.php`).
- Models are mixed between `app/` root and `app/Models/` (examples: `app/Ad.php`,
  `app/Course.php` vs `app/Models/`). Preserve existing namespaces when moving files
  or creating new models — the `App\` PSR-4 root maps to `app/`.
- Policies and authorization are implemented in `app/Policies/` (example: `app/Policies/CoursePolicy.php`).
- Background processing: queue worker commands are used in `composer run dev` (`php artisan queue:listen --tries=1`).

Data & persistence:
- Migrations: `database/migrations/` (timestamps like `2025_11_28_...`).
- Factories & seeders: `database/factories/` and `database/seeders/`.
- Composer post-create scripts may touch `database/database.sqlite` and run migrations.

Project-specific conventions and pitfalls for agents:
- Do NOT assume all models live under `App\Models`; check file namespace at top of file.
- When adding or renaming models, update any references (imports) and run `composer dump-autoload` if necessary.
- Controllers accept validated `FormRequest` classes from `app/Http/Requests/` — follow these for input validation.
- Policies are used for authorization; look in `app/Providers/AuthServiceProvider.php` for bindings before changing policy logic.

Tests & tooling:
- Tests live in `tests/` and the project uses `phpunit` / `artisan test`.
- Code style tooling: `laravel/pint` is included — run `./vendor/bin/pint` (or `composer run` if configured) when making formatting changes.

Integration points & externals:
- Composer packages defined in `composer.json` (Laravel 12, breeze, pint, sail, pail).
- Frontend dependencies via `package.json` and `npm` (Vite/Tailwind).
- Public assets are built into `public/build/` (Vite output).

How to make safe changes (short checklist for the agent):
1. Locate examples that follow the pattern you will change (e.g., look at `app/Ad.php` before adding a new Eloquent model).
2. Preserve namespace declarations and PSR-4 layout; if moving files, update imports and run `composer dump-autoload`.
3. For DB changes, create a migration in `database/migrations/` with the Laravel artisan generator and reference existing naming/timestamp style.
4. Run the repository's test suite: `composer run test` and do not commit failing tests.
5. If editing frontend assets, run `npm run build` (or `npm run dev` for local testing).

Files to inspect first when reasoning about a change:
- `composer.json` (scripts and PHP requirement)
- `app/` (controllers, models — note mixed placement)
- `app/Policies/` and `app/Providers/AuthServiceProvider.php` (authorization)
- `routes/web.php` and `routes/auth.php` (routing patterns)
- `database/migrations/`, `database/factories/`, `database/seeders/` (DB schema)
- `resources/` and `vite.config.js`, `tailwind.config.js` (frontend)

Notes about merging/updating existing instructions:
- No existing `.github/copilot-instructions.md` or AGENT files were found when this file was created. If there is an internal or private agent guideline, merge its unique rules here but preserve repository-specific commands and examples above.

If anything here is unclear or you'd like more examples (e.g., a short walkthrough for adding a new model + migration + controller + test), tell me which area and I will expand it.
