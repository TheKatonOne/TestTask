<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name'];

    public function parents()
    {
        return $this->belongsToMany(Organization::class, 'organization_relations', 'child_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(Organization::class, 'organization_relations', 'parent_id', 'child_id');
    }
}


