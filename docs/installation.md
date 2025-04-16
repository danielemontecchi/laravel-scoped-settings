# Installation

Laravel Scoped Settings can be easily installed via Composer and integrates automatically with Laravel’s package discovery system.

---

## 📦 Requirements

- PHP >= 8.1  
- Laravel 10 or 11  
- Composer

---

## 🚀 Install the package

Use Composer to install the package:

```bash
composer require danielemontecchi/laravel-scoped-settings
```

Laravel will auto-discover the service provider and facade.

---

## 🔧 Publish configuration (optional)

You can publish the default configuration and migration files using:

```bash
php artisan vendor:publish --tag="laravel-scoped-settings-config"
php artisan vendor:publish --tag="laravel-scoped-settings-migrations"
```

> This allows you to customize the database structure or config options as needed.

---

## 🗃️ Run the migration

Create the `settings` table:

```bash
php artisan migrate
```

---

## 🧪 Run tests (optional)

To make sure everything is working, you can run the test suite:

```bash
./vendor/bin/pest
```

---

## 📚 Continue with usage

Once installed, proceed to the [Usage guide](usage.md) to learn how to store and retrieve settings.