<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Tree;

class Location extends Model
{
    protected $fillable = ['name', 'status', 'image', 'created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function trees()
    {
        return $this->belongsToMany(Tree::class, 'location_tree', 'location_id', 'tree_id')
            ->withPivot('status', 'view_count')
            ->withTimestamps();
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function views()
    {
        return $this->hasMany(TreeView::class);
    }

    public function activeTrees()
    {
        return $this->belongsToMany(Tree::class, 'location_tree')
            ->where('status', 'active')
            ->orderBy('name');
    }
}
