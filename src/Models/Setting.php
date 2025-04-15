<?php

namespace DanieleMontecchi\LaravelScopedSettings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'scope_type',
        'scope_id',
        'group',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];
}
