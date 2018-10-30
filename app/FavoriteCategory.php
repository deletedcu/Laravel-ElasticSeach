<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['user_id', 'name']; //whitelist
}
