<?php

namespace DanieleMontecchi\LaravelScopedSettings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $scope_type
 * @property int|string|null $scope_id
 * @property string $group
 * @property string $key
 * @property mixed $value
 */
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
