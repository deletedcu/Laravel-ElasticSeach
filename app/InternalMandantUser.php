<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalMandantUser extends Model
{
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_id','role_id','user_id','mandant_id_edit',]; //whitelist
    
    // public function users(){
    //     return $this->hasOne('App\MandantInfo');
    // }
    
    public function mandant(){
        return $this->belongsTo('App\Mandant', 'mandant_id');
        // return $this->hasOne('App\Mandant', 'mandant_id');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
        // return $this->hasOne('App\User', 'user_id');
    }
    
    public function role(){
        return $this->belongsTo('App\Role', 'role_id');
    }
    
}
