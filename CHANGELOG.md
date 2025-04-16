# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [v1.0.2] – 2025-04-16

### ✨ Added

- `has($key)` method to check whether a key is explicitly stored in the current scope

### 📘 Docs

- Updated `api.md`, `usage.md`, and `README.md` with examples and explanation

## [v1.0.1] - 2025-04-16

### Changed

- Fix GitHub Actions deploy: configurazione corretta per push su branch `gh-pages` tramite token
- Piccole ottimizzazioni nel deploy script (`prepare-docs.sh`)

## [v1.0.0] - 2025-04-16

### Added

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
