<?php

namespace DanieleMontecchi\LaravelScopedSettings\Commands;

use Illuminate\Console\Command;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DumpSettingsCommand extends Command
{
    protected $signature = 'settings:dump
                            {--pretty : Pretty print JSON}
                            {--scope_type= : Filter by scope type}
                            {--scope_id= : Filter by scope id}';
    protected $description = 'Dump settings as JSON (useful for debugging or export)';

    public function handle(): int
    {
        $query = Setting::query();

        if ($type = $this->option('scope_type')) {
            $query->where('scope_type', $type);
        }

        if ($id = $this->option('scope_id')) {
            $query->where('scope_id', $id);
        }

        $settings = $query->get()->map(fn($setting) => [
            'scope_type' => $setting->scope_type,
            'scope_id' => $setting->scope_id,
            'group' => $setting->group,
            'key' => $setting->key,
            'value' => $setting->value,
        ]);

        $json = $settings->toJson($this->option('pretty') ? JSON_PRETTY_PRINT : 0);

        $this->line($json);

        return CommandAlias::SUCCESS;
    }
}
