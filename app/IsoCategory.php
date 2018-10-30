<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsoCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['iso_category_parent_id','name', 'slug', 'active', 'parent']; //whitelist
    
    public function isIsoCategoryParent(){
        return $this->hasMany('App\IsoCategory','iso_category_parent_id','id');
    }
    
    public function hasAllDocuments(){
        return $this->hasMany('App\Document','iso_category_id','id');
    }
}
