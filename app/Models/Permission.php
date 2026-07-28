<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['key', 'label'];

    public static function allKeys(): array
    {
        return static::pluck('key')->toArray();
    }
}
