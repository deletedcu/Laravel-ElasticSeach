<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristFileType extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','active']; //whitelist
    
    public function juristFileTypeUsers(){ 
        return $this->hasMany(JuristFileTypeUser::class);
    }
    public function juristFile(){ 
        return $this->hasMany(JuristFile::class,'jurist_file_type_id','id');
    }
    
}
