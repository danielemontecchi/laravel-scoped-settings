# Scoping

Scoped settings allow you to store and retrieve values that are linked to a specific Eloquent model, such as a `User`, `Team`, `Tenant`, or any other entity.

This feature is useful when you want personalized settings per user, customer, team, etc.

---

## 👤 Scoping to a model

You can associate a setting with a specific model using `for($model)`:

```php
$user = User::find(1);
setting()->for($user)->set('dashboard.layout', 'compact');

$layout = setting()->for($user)->get('dashboard.layout');
// 'compact'
```

This setting will only apply to that user.

---

## 🎯 Global vs Scoped

Without `for()`, settings are considered **global**:

```php
setting()->set('locale', 'en'); // Global
setting()->for($user)->set('locale', 'it'); // User-specific
```

You can retrieve the global value explicitly with:

```php
setting()->forGlobal()->get('locale');
```

---

## 👥 Using custom models

You can scope to any model that extends `Illuminate\Database\Eloquent\Model`:

```php
$team = Team::find(5);
setting()->for($team)->set('theme', 'dark');
```

---

## 🔄 Overriding global with scoped values

You can have the same key defined at different scopes:

```php
setting()->set('notifications.email', true);             // global
setting()->for($user)->set('notifications.email', false); // scoped
```

You decide at runtime which to use based on context.

---

## 🧪 Testing scoped values

```php
$user = User::factory()->create();

setting()->for($user)->set('timezone', 'Europe/Rome');

expect(setting()->for($user)->get('timezone'))->toBe('Europe/Rome');
```

---

## 💡 Tip

If you're using the settings in a controller or service and want to keep it scoped to the authenticated user:

```php
setting()->for(auth()->user())->get('some.preference');
```

---

## 📚 Continue with

- [Artisan Commands](artisan.md)
- [API Reference](api.md)