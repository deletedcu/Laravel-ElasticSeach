<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteDocument extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['document_group_id', 'user_id', 'favorite_categories_id']; //whitelist
}
