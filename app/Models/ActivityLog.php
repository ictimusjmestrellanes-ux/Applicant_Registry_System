<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Logger Method (Reusable)
    |--------------------------------------------------------------------------
    */

    public static function record($action, $module = null, $description = null)
    {
        if (!auth()->check()) {
            return;
        }

        self::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'module'     => $module,
            'description'=> $description,
            'user_agent' => request()->userAgent(),
        ]);
    }
}