<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentMandantRole extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['document_mandant_id','role_id']; //whitelist
}
