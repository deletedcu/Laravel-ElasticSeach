<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adressat extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','active']; //whitelist
}