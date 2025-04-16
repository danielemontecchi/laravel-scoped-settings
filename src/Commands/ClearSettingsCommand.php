<?php

namespace DanieleMontecchi\LaravelScopedSettings\Commands;

use Illuminate\Console\Command;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearSettingsCommand extends Command
{
    protected $signature = 'settings:clear
                            {--scope_type= : Filter by scope type}
                            {--scope_id= : Filter by scope id}';
    protected $description = 'Clear stored settings, globally or by scope';

    public function handle(): int
    {
        $query = Setting::query();

        if ($type = $this->option('scope_type')) {
            $query->where('scope_type', $type);
        }

        if ($id = $this->option('scope_id')) {
            $query->where('scope_id', $id);
        }

        $count = $query->count();
        $query->delete();

        $this->info("Deleted {$count} setting(s).");

        return CommandAlias::SUCCESS;
    }
}
