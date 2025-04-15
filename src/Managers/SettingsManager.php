<?php

namespace DanieleMontecchi\LaravelScopedSettings\Managers;

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Str;

class SettingsManager
{
    protected ?string $scopeType = null;
    protected ?int $scopeId = null;

    /**
     * Scope settings to a specific model (e.g. user, team, etc.).
     */
    public function for(object $model): static
    {
        $clone = clone $this;
        $clone->scopeType = get_class($model);
        $clone->scopeId = method_exists($model, 'getKey') ? $model->getKey() : null;

        return $clone;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        [$group, $key] = $this->parseKey($key);

        $setting = Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        return $setting?->value ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        [$group, $key] = $this->parseKey($key);

        Setting::updateOrCreate([
            'scope_type' => $this->scopeType,
            'scope_id' => $this->scopeId,
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
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->delete();
    }

    public function all(): array
    {
        return Setting::query()
            ->where($this->getScopeConditions())
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->group . '.' . $setting->key => $setting->value])
            ->toArray();
    }

    public function group(string $group): array
    {
        return Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->key => $setting->value])
            ->toArray();
    }

    protected function getScopeConditions(): array
    {
        return [
            'scope_type' => $this->scopeType,
            'scope_id' => $this->scopeId,
        ];
    }

    protected function parseKey(string $fullKey): array
    {
        return Str::contains($fullKey, '.')
            ? explode('.', $fullKey, 2)
            : ['default', $fullKey];
    }

    public function clearScope(): static
    {
        $this->scopeId = null;
        $this->scopeType = null;

        return $this;
    }

    public function forGlobal(): static
    {
        return $this->clearScope();
    }


}
