<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristFileTypeUser extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['jurist_file_type_id','user_id']; //whitelist
}
