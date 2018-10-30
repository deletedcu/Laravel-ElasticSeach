<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','category','value']; //whitelist
}
