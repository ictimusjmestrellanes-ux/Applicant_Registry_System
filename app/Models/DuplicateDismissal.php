<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuplicateDismissal extends Model
{
    protected $fillable = [
        'group_hash',
        'applicant_ids',
        'user_id',
    ];

    protected $casts = [
        'applicant_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}