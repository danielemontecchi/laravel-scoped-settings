# Usage

Laravel Scoped Settings provides a fluent and expressive API to store, retrieve and organize configuration values at runtime.

You can access the settings manager using:

- the `setting()` helper (recommended)
- or the `Setting::` facade

---

## ✅ Basic usage

### Set a global setting

```php
setting()->set('site.name', 'My Laravel App');
```

### Set a value with caching

```php
// Cache the setting for 30 minutes
setting()->set('site.enabled', true, 1800);

// Use config-defined TTL (if set), or no cache if null
setting()->set('site.enabled', true);
```

### Get a setting

```php
$siteName = setting()->get('site.name'); // 'My Laravel App'
// If a TTL is configured, the value will be cached automatically.
```

### Check if a setting exists

```php
if (setting()->has('ui.theme')) {
    // It was set
}
```

### Provide a default fallback

```php
$timezone = setting()->get('app.timezone', 'UTC');
```

### Forget a setting

```php
setting()->forget('site.name');
```

### Deleting all settings for a model


```php
setting()->for($user)->flush();
```

---

## 📦 Value types

Values are automatically serialized to/from JSON. You can store:

- Strings  
- Booleans  
- Integers  
- Arrays

```php
setting()->set('notifications.enabled', true);
setting()->set('ui.theme', ['color' => 'blue', 'mode' => 'dark']);

$theme = setting()->get('ui.theme'); // ['color' => 'blue', 'mode' => 'dark']
```

---

## 🧠 Key format and grouping

You can group settings using dot notation:

```php
setting()->set('seo.meta.title', 'Welcome!');
```

This will be stored as:

- group: `seo.meta`
- key: `title`

Or, if split:

```php
setting()->set('seo.meta_title', 'Welcome!');
```

Will be stored as:

- group: `seo`
- key: `meta_title`

You can later retrieve an entire group:

```php
setting()->group('seo');
// returns ['meta_title' => 'Welcome!']
```

---

## 🧪 Testing in your code

During testing you can easily assert settings:

```php
expect(setting()->get('notifications.enabled'))->toBeTrue();
```

---

## 📚 Next Steps

- [Scoping Settings](scoping.md)
- [Artisan Commands](artisan.md)
- [API Reference](api.md)