<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiRole extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = [ 'role_id', 'wiki_category_id' ]; //whitelist
    
}
