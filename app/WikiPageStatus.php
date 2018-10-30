<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiPageStatus extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable =  [ 'name' ]; //whitelist
}
