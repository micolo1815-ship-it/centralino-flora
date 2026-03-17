<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tree;
use App\Models\Location;

class TreeView extends Model
{
    protected $fillable = [
        'tree_id',
        'location_id',
        'ip_address',
        'user_agent',
        'view_date',
    ];
    public function tree(){ 
        return $this->belongsTo(Tree::class,     'tree_id'); 
    }
    public function location(){ 
        return $this->belongsTo(Location::class, 'location_id'); 
    }
}
