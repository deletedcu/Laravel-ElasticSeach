<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantUser extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_id','user_id']; //whitelist
    
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    
    public function mandant(){
        return $this->hasOne('App\Mandant','id','mandant_id');
    }
    
    public function role(){
        return $this->belongsToMany('App\Role', 'mandant_user_roles', 'mandant_user_id', 'role_id')->where('mandant_user_roles.deleted_at', null);
        // return $this->hasManyThrough('App\Role', 'App\MandantUserRole', 'role_id', 'id');
        // return $this->hasMany('App\MandantUserRole', 'mandant_user_id', 'id')->belongsTo('App\Role','id','role_id');
    }
    
    public function mandantUserRoles(){
        return $this->hasMany('App\MandantUserRole','mandant_user_id','id');
    }
}
