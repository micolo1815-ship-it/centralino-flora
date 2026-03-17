<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        // ... existing
        'firstname', 'middle_initial', 'lastname', 'email', 'position', 'school_year',
        'status', 'image_path', 'retain_same_person',
    ];

    // Relationship: This officer has one user
    public function user()
    {
        return $this->hasOne(User::class, 'officer_id');
    }

    // Optional: Scope for officers with active user
    public function scopeWithActiveUser ($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', 'active');
        });
    }
}