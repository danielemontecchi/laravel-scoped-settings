# Laravel Scoped Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/danielemontecchi/laravel-scoped-settings.svg?style=flat-square)](https://packagist.org/packages/danielemontecchi/laravel-scoped-settings)
[![Total Downloads](https://img.shields.io/packagist/dt/danielemontecchi/laravel-scoped-settings.svg?style=flat-square)](https://packagist.org/packages/danielemontecchi/laravel-scoped-settings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/danielemontecchi/laravel-scoped-settings/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/danielemontecchi/laravel-scoped-settings/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/danielemontecchi/laravel-scoped-settings/branch/main/graph/badge.svg?token=YOUR_TOKEN_HERE)](https://codecov.io/gh/danielemontecchi/laravel-scoped-settings)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Documentation](https://img.shields.io/badge/docs-available-brightgreen.svg?style=flat-square)](https://danielemontecchi.github.io/laravel-scoped-settings)

---

**Laravel Scoped Settings** is a lightweight Laravel package that provides a simple and elegant way to manage global and model-scoped application settings.

- ⚡ Supports **global settings** and **per-model scoped settings**
- 🎯 API via Facade and `setting()` helper
- ✅ Includes artisan commands and full test suite
- 📖 Comes with documentation via GitHub Pages (powered by MkDocs)

---

## 📦 Installation

You can install the package via Composer:

```bash
composer require danielemontecchi/laravel-scoped-settings
```

To publish and run the migrations:

```bash
php artisan vendor:publish --tag="laravel-scoped-settings-migrations"
php artisan migrate
```

---

## 🚀 Usage

```php
// Global settings
setting()->set('site.name', 'My App');
$siteName = setting()->get('site.name');

// Check if a setting exists (ignores fallback)
if (setting()->has('site.name')) {
    // the value is explicitly set
}

// Scoped settings (e.g. per user)
setting()->for($user)->set('dashboard.layout', 'compact');
$layout = setting()->for($user)->get('dashboard.layout');
```

You can also retrieve all flat or grouped settings:

```php
setting()->all(); // ['site.name' => 'My App']
setting()->for($user)->group('dashboard'); // ['layout' => 'compact']
```

Clear a specific key:

```php
setting()->forget('site.name');
```

---

## 🛠 Artisan Commands

```bash
php artisan settings:list     # Show all settings
php artisan settings:clear    # Clear all settings
php artisan settings:dump     # Dump settings to JSON
```

---

## 🧪 Testing

```bash
./vendor/bin/pest
```

Coverage reports are generated via `phpunit.xml` config and uploaded to [Codecov](https://codecov.io/).

---

## 📚 Documentation

Full documentation available at:

👉 [https://danielemontecchi.github.io/laravel-scoped-settings](https://danielemontecchi.github.io/laravel-scoped-settings)

Includes:

- Installation & Configuration
- Usage Examples
- Scoping Strategies
- Artisan Commands
- Advanced Tips

---

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
