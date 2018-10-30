<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristCategoryMetaField extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','jurist_category_meta_id','active']; //whitelist
}
