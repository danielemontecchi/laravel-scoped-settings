<?php

namespace DanieleMontecchi\LaravelScopedSettings\Commands;

use Illuminate\Console\Command;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportSettingsCommand extends Command
{
    protected $signature = 'settings:import
        {file : The JSON file to import}
        {--merge : Merge with existing settings}
        {--overwrite : Overwrite existing settings}';

    protected $description = 'Import settings from a JSON file (exported by settings:export)';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!File::exists($file)) {
            $this->error("The file '$file' does not exist.");
            return self::FAILURE;
        }

        $json = File::get($file);

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $this->error('Invalid JSON file.');
            return self::FAILURE;
        }

        $merge = $this->option('merge');
        $overwrite = $this->option('overwrite');

        if ($merge && $overwrite) {
            $this->error('You cannot use both --merge and --overwrite options.');
            return self::FAILURE;
        }

        $mode = $overwrite ? 'overwrite' : 'merge';

        if ($mode === 'overwrite') {
            Setting::query()->delete();
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $entry) {
                if (!isset($entry['group'], $entry['key'], $entry['value'])) {
                    continue; // skip invalid entries
                }

                $scopeType = $entry['scope_type'] ?? null;
                $scopeId = $entry['scope_id'] ?? null;

                $query = Setting::query()
                    ->where('group', $entry['group'])
                    ->where('key', $entry['key'])
                    ->where('scope_type', $scopeType)
                    ->where('scope_id', $scopeId);

                $existing = $query->first();

                if ($existing) {
                    $existing->value = $entry['value'];
                    $existing->save();

                    continue;
                }

                Setting::create([
                    'scope_type' => $scopeType,
                    'scope_id' => $scopeId,
                    'group' => $entry['group'],
                    'key' => $entry['key'],
                    'value' => $entry['value'],
                ]);
            }
        });

        $this->info("Settings imported successfully using '{$mode}' mode.");
        return self::SUCCESS;
    }
}
