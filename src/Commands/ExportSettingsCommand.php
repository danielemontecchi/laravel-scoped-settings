<?php

namespace DanieleMontecchi\LaravelScopedSettings\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;

/**
 * ExportSettingsCommand
 *
 * This Artisan command allows you to export settings to a JSON file.
 * You can export all settings, only global settings, or scoped ones by type and ID.
 *
 * @example
 * php artisan settings:export
 * php artisan settings:export --only-global
 * php artisan settings:export --scope-type=App\\Models\\User
 * php artisan settings:export --scope-type=App\\Models\\User --scope-id=1
 */
class ExportSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'settings:export
                            {--only-global : Export only global settings}
                            {--scope-type= : Export settings for a specific scope type}
                            {--scope-id= : Export settings for a specific scope ID (requires scope-type)}';

    /**
     * The console command description.
     */
    protected $description = 'Export settings to a JSON file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $onlyGlobal = $this->option('only-global');
        $scopeType = $this->option('scope-type');
        $scopeId = $this->option('scope-id');

        $query = Setting::query();

        if ($onlyGlobal) {
            $query->whereNull('scope_type')->whereNull('scope_id');
        } elseif ($scopeType) {
            $query->where('scope_type', $scopeType);

            if ($scopeId !== null) {
                $query->where('scope_id', $scopeId);
            }
        }

        $settings = $query->get()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'group' => $setting->group,
                'scope_type' => $setting->scope_type,
                'scope_id' => $setting->scope_id,
            ];
        });

        $filename = now()->format('Ymd_His') . '_settings_export.json';
        Storage::disk('local')->put("settings/{$filename}", $settings->toJson(JSON_PRETTY_PRINT));

        $this->info("✅ Settings exported successfully to settings/{$filename}");
    }
}
