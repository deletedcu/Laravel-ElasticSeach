<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCoauthor extends Model
{
    // use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['user_id','document_id']; //whitelist
}
