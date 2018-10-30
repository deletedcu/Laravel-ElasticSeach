<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    const JURISTADMINISTRATOR = 35;
    const JURISTBENUTZER = 36;
    const JURISTENDOKUMENTANLEGER = 38;
    
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['name','mandant_required','admin_role','system_role','mandant_role','wiki_role']; //whitelist
    
    public function hasRole(){
        
    }
    
    public function isManager(){
        
    }
    
    public function mandantUserRoles(){ 
        return $this->belongsTo('App\MandantUserRole');
    }
    
    public function mandantUserRolesAll(){ 
        return $this->hasMany('App\MandantUserRole');
    }
    
    public function internalMandantUsers(){
        return $this->belongsTo('App\InternalMandantUser');
        // return $this->hasManyThrough('App\InternalMandantUser','App\User');
    }
}
