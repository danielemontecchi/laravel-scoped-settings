# Changelog

All notable changes to **Laravel Scoped Settings** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.3] - 2025-04-17
### Changed
- Refactored deployment logic and documentation generation via GitHub Actions.
- Improved `prepare-docs.sh` for more robust and automated publishing.

### Added
- Auto-generation of `latest` alias and `index.html` redirect.
- GitHub issue templates for bugs, features, docs, and questions.
- Label sync workflow based on `.github/labels.yml`.

---

## [1.0.2] - 2025-04-16
### Added
- `flush()` method to delete all scoped settings for a model.
- `has($key)` method to check if a setting is explicitly set.

---

## [1.0.1] - 2025-04-16
### Fixed
- Minor bug fix in scoped retrieval logic.

### Changed
- Improved stability in fallback resolution.

---

## [1.0.0] - 2025-04-16
### Added
- Initial release with support for global and scoped settings.
- `setting()` helper and Facade access.
- CRUD methods: `get`, `set`, `forget`, `all`, `group`.
- Model scoping via `for($model)`.
- Artisan commands:
  - `settings:list`
  - `settings:clear`
  - `settings:dump`
- Full test suite with Pest.
- MkDocs-powered documentation site.

---
