# Artisan Commands

Laravel Scoped Settings comes with helpful Artisan commands to inspect, export, and clear settings directly from the CLI.

These commands are useful for debugging, backup/restore workflows, or administrative tasks.

---

## 📋 List all settings

```bash
php artisan settings:list
```

Displays a table of all stored settings, including their scope (if any), group, key, and value.

Example output:

```
+----+--------------------+----------+------------+---------------+--------+
| ID | Scope Type         | Scope ID | Group      | Key           | Value  |
+----+--------------------+----------+------------+---------------+--------+
| 1  | NULL               | NULL     | site       | name          | Laravel|
| 2  | App\Models\User    | 1        | ui         | theme         | dark   |
+----+--------------------+----------+------------+---------------+--------+
```

---

## 🧹 Clear settings

```bash
php artisan settings:clear
```

This command deletes **all settings**.

You can also target scoped settings:

```bash
php artisan settings:clear --scope_type="App\\Models\\User" --scope_id=1
```

This will delete only the settings for the given model instance.

---

## 🧾 Export settings

```bash
php artisan settings:dump
```

This will output all settings as a JSON array to the console. Example:

```json
[
  {
    "scope_type": null,
    "scope_id": null,
    "group": "site",
    "key": "name",
    "value": "Laravel"
  },
  {
    "scope_type": "App\\Models\\User",
    "scope_id": 1,
    "group": "ui",
    "key": "theme",
    "value": "dark"
  }
]
```

To pretty-print the JSON:

```bash
php artisan settings:dump --pretty
```

To filter by scope:

```bash
php artisan settings:dump --scope_type="App\\Models\\User" --scope_id=1
```

---

## 💡 Tips

- Use `settings:clear` before re-importing from a backup
- Combine `settings:dump` with redirects: `> backup.json`
- Use the output to migrate settings across environments

---

## 📚 Next

- [API Reference](api.md)