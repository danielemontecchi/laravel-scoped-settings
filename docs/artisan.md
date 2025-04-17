# Artisan Commands

Laravel Scoped Settings comes with helpful Artisan commands to inspect, export, and clear settings directly from the
CLI.

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

### Export Settings

```bash
php artisan settings:export
```

Exports all settings to `storage/app/settings/YYYYMMDD_HHMMSS_settings_export.json`

**Options:**

- `--only-global` → export only global settings
- `--scope-type=...` → export by model type
- `--scope-id=...` → export by model type + ID

### Import Settings

```bash
php artisan settings:import path/to/file.json
```

Imports settings from a previously exported JSON file.

**Options:**

- `--merge` → update existing values (default)
- `--overwrite` → delete existing and fully replace

---

## 💡 Tips

- Use `settings:clear` before re-importing from a backup
- Combine `settings:dump` with redirects: `> backup.json`
- Use the output to migrate settings across environments

---

## 📚 Next

- [API Reference](api.md)