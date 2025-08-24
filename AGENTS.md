# Repository Guidelines

## Project Structure & Module Organization
- `app/`: Application code (controllers in `App\\Http\\Controllers`, Livewire in `app/Livewire`).
- `routes/web.php`: HTTP routes and endpoints.
- `resources/views`: Blade templates; Livewire views in `resources/views/livewire/`.
- `database/`: Migrations, factories, seeders; local SQLite at `database/database.sqlite`.
- `public/`: Web root (`public/index.php`) and static assets (CSS/JS/images).
- `tests/`: PHPUnit tests under `tests/Feature` and `tests/Unit`.
- `config/`, `bootstrap/`, `storage/`: Standard Laravel config and runtime files.

## Build, Test, and Development Commands
- Install PHP deps: `composer install`.
- Configure env: `cp .env.example .env && php artisan key:generate`.
- Migrate DB: `php artisan migrate --graceful`.
- Dev stack (server, queue, logs): `composer run dev`.
- Alternative local server: `php artisan serve`.
- Run tests: `composer test` (clears config, runs `php artisan test`).
- Storage link for uploads: `php artisan storage:link`.

## Coding Style & Naming Conventions
- Indentation: 4 spaces (`.editorconfig`).
- PHP style: PSR-12 via Laravel Pint; format with `./vendor/bin/pint`.
- Namespaces: PSR-4 under `App\\...`.
- Blade views: kebab-case filenames in `resources/views` (e.g., `photo-booth.blade.php`).
- Livewire: PHP in `app/Livewire`, paired Blade in `resources/views/livewire/*.blade.php`.
- Static assets: place CSS/JS in `public/` and include via Blade `asset()`; no build step required.

## Testing Guidelines
- Framework: PHPUnit via `php artisan test`.
- Location: Unit in `tests/Unit`, feature in `tests/Feature`.
- Naming: `*Test.php` extending `Tests\\TestCase`.
- Tips: Use factories/seeders; filter with `php artisan test --filter SomeTest`.

## Commit & Pull Request Guidelines
- Commits: short, imperative subject (e.g., `fix: correct QR code size`).
- PRs: clear description, linked issues, screenshots for UI, and test plan.
- Requirements: all tests pass, code formatted with Pint; note migrations and provide rollback steps.

## Security & Configuration Tips
- Do not commit `.env` or secrets; use environment variables.
- Rotate `APP_KEY` on new setups: `php artisan key:generate`.
- Ensure uploads work by creating the storage symlink: `php artisan storage:link`.
