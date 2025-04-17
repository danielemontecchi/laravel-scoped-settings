<?php

namespace DanieleMontecchi\LaravelScopedSettings\Managers;

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

/**
 * Class SettingsManager
 *
 * Handles global and scoped application settings with optional caching and fallback.
 */
class SettingsManager
{
    /** @var string|null Type of the scope (e.g. 'user', 'team') */
    protected ?string $scopeType = null;

    /** @var int|string|null ID of the scope model */
    protected int|string|null $scopeId = null;

    /**
     * Define a scope for the settings.
     *
     * @param object $model The model to scope the settings to.
     * @return static
     */
    public function for(object $model): static
    {
        $clone = clone $this;
        $clone->scopeType = get_class($model);
        $clone->scopeId = method_exists($model, 'getKey') ? $model->getKey() : null;
        return $clone;
    }

    /**
     * Retrieve a setting by key with optional default and fallback logic.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        [$group, $key] = $this->parseKey($key);
        $cacheKey = $this->getCacheKey("{$group}.{$key}");

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $value = $this->getScopedValue($group, $key);

        if (!is_null($value)) {
            return $value;
        }

        if (!is_null($default)) {
            $this->set("{$group}.{$key}", $default);
            return $default;
        }

        if (config('scoped-settings.fallback_to_global', false) && $this->isScoped()) {
            $globalSetting = (new static)->get("{$group}.{$key}");
            if (!is_null($globalSetting)) {
                $this->set("{$group}.{$key}", $globalSetting);
                return $globalSetting;
            }
        }

        return null;
    }

    /**
     * Store a setting with optional caching.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Cache time in seconds, null to use config defaults or skip cache.
     * @return void
     */
    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        [$group, $key] = $this->parseKey($key);

        Setting::query()->updateOrCreate(
            array_merge($this->getScopeConditions(), compact('group', 'key')),
            ['value' => $value]
        );

        $ttl = $ttl ?? config('scoped-settings.cache.ttl.' . ($this->isScoped() ? 'scoped' : 'global'));

        if (!is_null($ttl)) {
            Cache::put($this->getCacheKey("{$group}.{$key}"), $value, $ttl);
        }
    }

    /**
     * Forget a setting by key.
     *
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        [$group, $key] = $this->parseKey($key);

        Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->delete();

        Cache::forget($this->getCacheKey("{$group}.{$key}"));
    }

    /**
     * Clear all scoped or global settings.
     *
     * @return void
     */
    public function flush(): void
    {
        Setting::query()->where($this->getScopeConditions())->delete();
    }

    /**
     * Determine if a setting exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return !is_null($this->get($key));
    }

    /**
     * Get all settings as array.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Setting::query()
            ->where($this->getScopeConditions())
            ->get()
            ->mapWithKeys(fn($setting) => ["{$setting->group}.{$setting->key}" => $setting->value])
            ->toArray();
    }

    /**
     * Get all settings from a specific group.
     *
     * @param string $group
     * @return array<string, mixed>
     */
    public function group(string $group): array
    {
        return Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->key => $setting->value])
            ->toArray();
    }

    /**
     * Determine whether the manager is scoped.
     *
     * @return bool
     */
    public function isScoped(): bool
    {
        return !is_null($this->scopeType) && !is_null($this->scopeId);
    }

    /**
     * Get the current scope conditions.
     *
     * @return array<string, mixed>
     */
    protected function getScopeConditions(): array
    {
        return $this->isScoped()
            ? ['scope_type' => $this->scopeType, 'scope_id' => $this->scopeId]
            : ['scope_type' => null, 'scope_id' => null];
    }

    /**
     * Parse a full key into group and key.
     *
     * @param string $fullKey
     * @return array{string, string}
     */
    protected function parseKey(string $fullKey): array
    {
        return Str::contains($fullKey, '.')
            ? explode('.', $fullKey, 2)
            : ['default', $fullKey];
    }

    /**
     * Clear current scope.
     *
     * @return $this
     */
    public function clearScope(): static
    {
        $this->scopeType = null;
        $this->scopeId = null;
        return $this;
    }

    /**
     * Set global scope.
     *
     * @return $this
     */
    public function forGlobal(): static
    {
        return $this->clearScope();
    }

    /**
     * Generate cache key.
     *
     * @param string $key
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        $prefix = $this->isScoped() ? "{$this->scopeType}:{$this->scopeId}" : 'global';
        return "scoped-settings:{$prefix}:{$key}";
    }

    /**
     * Retrieve a setting value from the DB for current scope.
     *
     * @param string $group
     * @param string $key
     * @return mixed|null
     */
    public function getScopedValue(string $group, string $key): mixed
    {
        $setting = Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        return $setting?->value;
    }
}
