<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristCategoryMeta extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','active','beratung']; //whitelist
    
    public function metaInfos(){
        return $this->hasMany(JuristCategoryMetaField::class,'jurist_category_meta_id','id');
    }
    
    public function documents(){
        return $this->hasMany(Document::class,'jurist_category_meta_id','id');
    }
}
