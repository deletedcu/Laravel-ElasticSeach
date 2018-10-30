<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristDocumentsMandant extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['jurist_file_id','mandant_id',]; //whitelist
}
