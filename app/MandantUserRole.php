<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantUserRole extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_user_id','role_id']; //whitelist
    
    public function mandantUsers(){
        return $this->hasMany('App\MandantUser','mandant_user_id','id');
    }
    
    public function mandantUser(){
        return $this->belongsTo('App\MandantUser','mandant_user_id','id');
    }
    public function mUser(){
        return $this->hasOne('App\MandantUser','id','mandat_user_id');
    }
    
    public function roles(){
        return $this->hasMany('App\Role','id','role_id');
    }
    
    public function role(){
        return $this->hasOne('App\Role','id','role_id');
    }
}
