<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['slug', 'label'];

    public static function allSlugs(): array
    {
        return static::pluck('slug')->toArray();
    }
}
