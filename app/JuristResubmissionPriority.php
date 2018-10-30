<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristResubmissionPriority extends Model
{
    protected $fillable = ['name', 'color', 'bgcolor'];
    
    public function hasAllDocuments(){
        return $this->hasMany('App\JuristFileResubmission','jurist_resubmission_priority_id','id');
    }
}
