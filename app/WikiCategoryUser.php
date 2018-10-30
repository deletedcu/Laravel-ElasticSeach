<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiCategoryUser extends Model
{
        protected $guarded = []; //blacklist
    protected $fillable = [ 'user_id', 'wiki_category_id' ]; //whitelist
}
