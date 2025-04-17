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

        DB::transaction(function () use ($data, $mode) {
            foreach ($data as $entry) {
                if (!isset($entry['group'], $entry['key'], $entry['value'])) {
                    continue; // skip invalid entries
                }

                $query = Setting::query()
                    ->where('group', $entry['group'])
                    ->where('key', $entry['key']);

                if (isset($entry['scope_type'], $entry['scope_id'])) {
                    $query->where('scope_type', $entry['scope_type'])
                        ->where('scope_id', $entry['scope_id']);
                } else {
                    $query->whereNull('scope_type')->whereNull('scope_id');
                }

                $existing = $query->first();

                if ($mode === 'overwrite' && $existing) {
                    $existing->delete();
                    $existing = null;
                }

                if (!$existing) {
                    $normalized = collect($data)
                        ->map(fn($item) => array_merge([
                            'scope_type' => null,
                            'scope_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ], $item)
                        );

                    // Optionally split global and scoped, but not strictly necessary with this normalization
                    Setting::insert($normalized->toArray());
                } elseif ($mode === 'merge') {
                    $existing->value = $entry['value'];
                    $existing->save();
                }
            }
        });

        $this->info("Settings imported successfully using '{$mode}' mode.");
        return self::SUCCESS;
    }
}
