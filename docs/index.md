# Laravel Scoped Settings

**Laravel Scoped Settings** is a powerful and flexible package for managing configuration and preferences at both the global level and the model (user, team, etc.) level. It provides a clean API for setting, getting, and organizing data using scopes and logical groups.

---

## ✨ Key Features

- ✅ Store settings globally or per Eloquent model  
- ✅ Simple fluent API using `setting()` helper or `Setting::` facade  
- ✅ Support for nested grouping using dot notation (e.g. `notifications.email`)  
- ✅ Automatic casting of scalars and arrays (JSON handling)  
- ✅ Artisan commands to list, clear and export settings  
- ✅ Built-in support for versioned documentation via [mike](https://github.com/jimporter/mike)

---

## 🧠 When to use this package

Use Laravel Scoped Settings when you need to:

- Store **application-wide configurations** editable at runtime
- Allow **user-specific preferences**, like UI themes or notification settings
- Enable **team or organization scoped settings**
- Group settings logically and retrieve them with minimal code
- Handle per-user fallback to global defaults with manual logic

---

## ⚙️ Example use cases

- UI preferences (light/dark mode, layout)
- Notification toggles (email, push)
- Feature flags per customer or team
- Custom limits, regional/localization settings
- Multi-tenant configurations

---

## 📦 Requirements

- PHP >= 8.1  
- Laravel 10 or 11  
- Composer, Git, and optionally GitHub Pages for docs

---

## 📚 Next Steps

- [Installation](installation.md)
- [How to Use](usage.md)
- [Scoping Settings](scoping.md)
- [Artisan Commands](artisan.md)
- [API Reference](api.md)
- [Changelog](changelog.md)

---

> This package is maintained by [Daniele Montecchi](https://github.com/danielemontecchi) and released under the MIT License.
