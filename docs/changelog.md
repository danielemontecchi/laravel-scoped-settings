# Changelog

Below are the available documentation versions, with their corresponding changes.

---

## [Latest](https://danielemontecchi.github.io/laravel-scoped-settings/)

Alias for the most recent stable version of the documentation.

---

## [v1.0.2](https://danielemontecchi.github.io/laravel-scoped-settings/v1/) – 2025-04-16

### ✨ Added

- `has($key)` method to check whether a key is explicitly stored in the current scope

### 📘 Docs

- Updated `api.md`, `usage.md`, and `README.md` with examples and explanation

## [v1.0.1](https://danielemontecchi.github.io/laravel-scoped-settings/v1/) – 2025-04-16

### 🔧 Fixes & Improvements

- Fixed GitHub Actions permissions to allow `gh-pages` deployment via `GH_TOKEN`
- Enhanced `prepare-docs.sh` compatibility by fixing unsupported grep usage
- Documentation deploy now fully automated on new tags via `mike deploy`

## [v1.0.0](https://danielemontecchi.github.io/laravel-scoped-settings/v1/) – 2025-04-16

### ✨ Features

- Global and model-scoped settings management with fluent API
- `setting()` helper and `Setting::` facade for easy access
- JSON-based value storage with automatic type casting
- Dot notation support for grouped key organization
- Ability to scope settings to any Eloquent model (user, team, etc.)
- Artisan commands: `settings:list`, `settings:clear`, `settings:dump`
- Fully versioned documentation using MkDocs + mike
- Version warning banners for outdated docs
- GitHub Actions for testing and docs deployment
- README with badges, install instructions, and usage examples

---
