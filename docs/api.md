# API Reference

This section describes the core methods available through the `SettingsManager` class, accessible via the `setting()` helper or the `Setting` facade.

---

## `set(string $key, mixed $value): void`

Sets a setting value.

```php
setting()->set('app.locale', 'en');
```

The `$value` can be a string, boolean, number, or array.

---

## `get(string $key, mixed $default = null): mixed`

Gets a setting value. If not found, returns the `$default`.

```php
$value = setting()->get('app.locale', 'en');
```

---

## `forget(string $key): void`

Deletes a specific setting.

```php
setting()->forget('app.locale');
```

---

### `has(string $key): bool`

Check if a setting exists for the given key. This method respects the current scope (global or model).

```php
setting()->has('app.debug'); // true or false
setting()->for($user)->has('notifications.enabled'); // true or false
```

Useful for conditional logic based on whether a setting has been stored or overridden.

---

### `flush(): void`

Delete all settings associated with the current scope. It is useful for resetting settings for a specific model.

```php
setting()->for($user)->flush();
```

This will remove all keys stored for the provided scope (e.g., the `$user` model).

---

## `all(): array`

Returns all settings as a flattened array (including group + key):

```php
[
  "site.name" => "My App",
  "ui.theme" => "dark"
]
```

---

## `group(string $group): array`

Returns all settings within a given group:

```php
setting()->group('ui');
// ['theme' => 'dark']
```

---

## `for(Model $model): SettingsManager`

Scopes the manager to a specific Eloquent model.

```php
setting()->for($user)->set('locale', 'it');
```

---

## `forGlobal(): SettingsManager`

Resets the manager to the global scope explicitly.

```php
setting()->forGlobal()->get('site.name');
```

---

## `clearScope(): SettingsManager`

Clears the current scope from the instance.

```php
setting()->for($user)->clearScope()->get('site.name');
```

---

## 💡 Notes

- All keys are parsed as `group.key` internally  
- Setting values are serialized as JSON  
- Scoped values are isolated from global ones

---

## 📚 You may also like

- [Usage](usage.md)
- [Scoping](scoping.md)
- [Artisan Commands](artisan.md)
