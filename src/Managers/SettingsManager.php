<?php

namespace DanieleMontecchi\LaravelScopedSettings\Managers;

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

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

        $cacheKey = $this->getCacheKey($key);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $setting = Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        $value = $setting?->value ?? $default;

        // Optional cache: if TTL is defined in config, store it
        $ttl = is_null($this->scopeType)
            ? config('scoped-settings.cache.global_ttl')
            : config('scoped-settings.cache.scoped_ttl');

        if (!is_null($ttl)) {
            Cache::put($cacheKey, $value, $ttl);
        }

        return $value;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
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

        // Cache logic
        $cacheKey = $this->getCacheKey($key);
        if (is_null($ttl)) {
            $ttl = is_null($this->scopeType)
                ? config('scoped-settings.cache.global_ttl')
                : config('scoped-settings.cache.scoped_ttl');
        }

        if (!is_null($ttl)) {
            Cache::put($cacheKey, $value, $ttl);
        }

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

    public function flush(): void
    {
        Setting::query()
            ->where($this->getScopeConditions())
            ->delete();
    }

    public function has(string $key): bool
    {
        [$group, $key] = $this->parseKey($key);

        return Setting::query()
            ->where($this->getScopeConditions())
            ->where('group', $group)
            ->where('key', $key)
            ->exists();
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


    protected function getCacheKey(string $key): string
    {
        $scopePrefix = is_null($this->scopeType)
            ? 'global'
            : strtolower(class_basename($this->scopeType)) . ':' . $this->scopeId;

        return "scoped-settings:{$scopePrefix}:{$key}";
    }
}
