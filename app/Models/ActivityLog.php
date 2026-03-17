<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'event',
        'type_id',
        'subject_id',
        'subject_type',
        'ip_address',
        'user_agent',
    ];

    // Relation to activity type
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // If you have a polymorphic subject relationship, also define:
    public function subject()
    {
        return $this->morphTo();
    }
    // And if you have a type relationship:
    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }
}