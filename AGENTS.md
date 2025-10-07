# Repository Guidelines

## Project Structure & Module Organization
- Root `www/` holds production PHP plus shared assets; folders outside it are editor configs.
- Domain modules live in `QC/`, `p_inspection/`, `RnD/`, `work/`, etc.; place new features beside their closest peers.
- Shared helpers sit in `common/`, `func.php`, `load_*.php`, and `session.php`; extend these rather than duplicating auth or formatting logic.
- Front-end assets belong in `css/`, `js/`, `assets/`, and module-specific `*/img/` folders; stay aligned with the tokens in `design.md`.
- Third-party code comes from Composer in `vendor/` and legacy JS bundles like `jqGrid-master/`; never edit vendor files directly.

## Build, Test, and Development Commands
- `composer install` — install PHP dependencies (dompdf, Google API clients).
- `php -S 127.0.0.1:8000 -t www` — serve the portal locally; mock remote services when staging is offline.
- `npm install && npm run build` within `www/react/` when updating the React prototype; commit the compiled assets in `www/react/dist/`.

## Coding Style & Naming Conventions
- Use 4-space indentation for PHP and JavaScript and keep HTML closing tags aligned with their opener.
- Name PHP scripts with snake_case (`load_statistics_ceiling.php`) and CSS classes with kebab-case (`modern-dashboard-table`).
- Reuse the CSS custom properties in `css/dashboard-style.css` and `design.md`; avoid hard-coded colors or shadows.
- Wrap new browser logic inside module-level IIFEs in `common.js` or dedicated scripts to protect the global scope.

## Testing Guidelines
- No global PHPUnit config yet; if you add one, run `vendor/bin/phpunit --configuration <file>` and commit it with the tests.
- Exercise affected dashboards through the local PHP server and log manual regression steps in `CURRENT.md`.
- For data-tied modules, seed representative rows in `data/` fixtures or include SQL snippets in the PR body.

## Commit & Pull Request Guidelines
- Keep commit subjects short and imperative (e.g., `align qc dashboard colors`), mirroring the existing history.
- Reference related issue or ticket IDs in the body and attach before/after screenshots for UI-facing work.
- PRs should list schema or session impacts plus the manual or automated tests you performed.

## Security & Configuration Tips
- Session handling lives in `session.php`; coordinate with maintainers before changing redirects or level checks.
- Keep credentials and personal data out of Git; stash experiments in ignored files under `data/`.
- Reuse `func.php` and `common/` utilities before adding new sanitization or date helpers.
