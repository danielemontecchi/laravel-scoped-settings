<?php

namespace DanieleMontecchi\LaravelScopedSettings\Commands;

use Illuminate\Console\Command;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ListSettingsCommand extends Command
{
    protected $signature = 'settings:list';
    protected $description = 'List all stored settings';

    public function handle(): int
    {
        $settings = Setting::all(['id', 'scope_type', 'scope_id', 'group', 'key', 'value']);

        if ($settings->isEmpty()) {
            $this->info('No settings found.');
            return CommandAlias::SUCCESS;
        }

        $this->table(
            ['ID', 'Scope Type', 'Scope ID', 'Group', 'Key', 'Value'],
            $settings->toArray()
        );

        return CommandAlias::SUCCESS;
    }
}
