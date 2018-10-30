<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    const ENTWURF = 1;
    const AKTUELL = 3;
    const ARCHIVE = 5;
    protected $guarded = []; //blacklist
    protected $fillable = ['name']; //whitelist
}
