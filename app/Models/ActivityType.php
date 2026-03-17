<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $table = 'activity_types';

    // Mass assignable attributes
    protected $fillable = [
        'name',
    ];

    /**
     * Get the activity logs associated with this type.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'type_id');
    }
}
