<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mandant extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'name','kurzname','mandant_number','rights_wiki', 'geschaftsfuhrer', 'geschaftsfuhrer_infos', 'geschaftsfuhrer_von', 'geschaftsfuhrer_bis',
        'rights_admin','logo','mandant_id_hauptstelle','hauptstelle',
        'adresszusatz','strasse','plz','hausnummer','ort','bundesland','telefon',
        'kurzwahl','fax','email','website','geschaftsfuhrer_history','active', 'edited_by'
    ]; //whitelist
    
    public function mandantInfo(){
        if(null !== $this->hasOne('App\MandantInfo'))
            return $this->hasOne('App\MandantInfo');
        else return '';
    }
    
    public function mandantUsers(){
        
        return $this->hasMany('App\MandantUser', 'mandant_id', 'id')->groupBy('user_id');
    }
    
    public function users(){
        // return $this->hasManyThrough('App\User', 'App\MandantUser','mandant_id','id');
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null);
    }
    
    public function usersOrderByLastName(){
        // return $this->hasManyThrough('App\User', 'App\MandantUser','mandant_id','id');
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')
        ->orderBy('last_name','asc')->where('mandant_users.deleted_at', null);
    }
    public function usersWithTrashed(){
        // return $this->hasManyThrough('App\User', 'App\MandantUser','mandant_id','id');
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->withTrashed();
    }
    
    public function usersActive(){
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null)->where('active', true);
    }
    
    public function usersInactive(){
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null)->where('active', false);
    }
    
    public function internalUsers(){
        return $this->hasMany('App\InternalMandantUser', 'mandant_id', 'id');
    }
    
}
