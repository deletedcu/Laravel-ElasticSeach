<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristFileResubmission extends Model
{
    public function file_name()
    {
        return $this->hasOne('App\JuristFile');
    }
}
