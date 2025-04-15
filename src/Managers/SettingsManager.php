<?php

namespace DanieleMontecchi\LaravelScopedSettings\Managers;

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Str;

class SettingsManager
{
    protected ?object $scope = null;

    /**
     * Define a model to scope settings to (ex. User)
     */
    public function for(object $model): static
    {
        $this->scope = $model;

        return $this;
    }

    public function clearScope(): static
    {
        $this->scope = null;

        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        [$group, $key] = $this->parseKey($key);

        $setting = Setting::query()
            ->when($this->scope, fn($q) => $q
                ->where('scope_type', get_class($this->scope))
                ->where('scope_id', $this->scope->getKey()))
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        return $setting?->value ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        [$group, $key] = $this->parseKey($key);

        Setting::updateOrCreate([
            'scope_type' => $this->scope ? get_class($this->scope) : null,
            'scope_id' => $this->scope?->getKey(),
            'group' => $group,
            'key' => $key,
        ], [
            'value' => $value,
        ]);
    }

    public function forget(string $key): void
    {
        [$group, $key] = $this->parseKey($key);

        Setting::query()
            ->when($this->scope, fn($q) => $q
                ->where('scope_type', get_class($this->scope))
                ->where('scope_id', $this->scope->getKey()))
            ->where('group', $group)
            ->where('key', $key)
            ->delete();
    }

    public function all(): array
    {
        return Setting::query()
            ->when($this->scope, fn($q) => $q
                ->where('scope_type', get_class($this->scope))
                ->where('scope_id', $this->scope->getKey()))
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->group . '.' . $setting->key => $setting->value])
            ->toArray();
    }

    public function group(string $group): array
    {
        return Setting::query()
            ->when($this->scope, fn($q) => $q
                ->where('scope_type', get_class($this->scope))
                ->where('scope_id', $this->scope->getKey()))
            ->where('group', $group)
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->key => $setting->value])
            ->toArray();
    }

    protected function parseKey(string $fullKey): array
    {
        return Str::contains($fullKey, '.')
            ? explode('.', $fullKey, 2)
            : ['default', $fullKey];
    }
}
