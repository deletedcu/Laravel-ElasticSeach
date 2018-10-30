<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = [ 'name', 'top_category','all_roles' ]; //whitelist
    
    public function wikiRoles(){
        return $this->hasMany('App\WikiRole','wiki_category_id','id');
    }
    
    
    public function wikiCategoryUsers(){
        return $this->hasMany('App\WikiCategoryUser','wiki_category_id','id');
    }
}
