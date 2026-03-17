<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    protected $fillable = [
        'name',
        'scientific_name',
        'common_name',
        'local_name',
        'location',
        'description',
        'uses_filipino',
        'tree_facts',
        'tagged_trees',
        'domain',
        'kingdom',
        'phylum',
        'class',
        'order',
        'family',
        'genus',
        'species',
        'cover_image',
        'image_gallery',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'image_gallery' => 'array',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_tree', 'tree_id', 'location_id')
            ->withPivot('status', 'view_count')
            ->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function views()
    {
        return $this->hasMany(TreeView::class);
    }
}
