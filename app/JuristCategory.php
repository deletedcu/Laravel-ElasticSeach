<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['jurist_category_parent_id','name', 'slug', 'active', 'parent','beratung']; //whitelist
    
    public function isJuristCategoryParent(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',0);
    }
    
    public function juristenParent(){
        return $this->belongsTo('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',0);
    }
    
    public function juristCategories(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',0);
    }
    public function juristCategoriesActive(){//rechablage
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('active',1)->where('beratung',0);
    }
    
    public function isJuristCategoryBeratungParent(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',1);
    }
    
    public function juristenBeratungParent(){
        return $this->belongsTo('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',1);
    }
    
    public function juristCategoriesBeratung(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('beratung',1);
    }
    public function juristCategoriesBeratungActive(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('active',1)->where('beratung',1);
    }
    
    public function hasAllDocuments(){
        return $this->hasMany('App\Document','jurist_category_id','id');
    }
    
    public function juristCategoryMetaFields(){
        return $this->hasMany('App\Document','jurist_category_id','id');
    }
  
}
