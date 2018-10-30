<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'username_sso', 'email', 'password', 'title', 'first_name', 'last_name', 'short_name', 'email_reciever', 'picture', 'active', 'active_from', 'active_to', 'birthday', 'created_by', 
    'last_login', 'phone', 'phone_short', 'email_private', 'email_work', 'phone_mobile', 'position', 'abteilung', 'informationen'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $dates = ['last_login', 'last_login_history', 'created_at', 'updated_at'];
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getBirthdayAttribute($value)    {
        
        if (empty($value) || $value == '0000-00-00') return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getActiveFromAttribute($value)
    {
        if (empty($value)) return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getActiveToAttribute($value)
    {
        if (empty($value)) return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setBirthdayAttribute($value)
    {
        if (empty($value)) $this->attributes['birthday'] = null;
        else $this->attributes['birthday'] = Carbon::parse($value);
    }
    /**
     * Get the timestamp for last login
     *
     * @param  string  $value
     * @return string
     */
    public function getLastLoginAttribute($value)
    {
        if (empty($value)) return null;
        else return Carbon::parse($value);
    }
    
    /**
     * Set the timestamp for last login
     *
     * @param  string  $value
     * @return string
     */
    public function setLastLoginAttribute($value)
    {
        if (empty($value) || $value == null) $this->attributes['last_login'] = null;
        else $this->attributes['last_login'] = Carbon::parse($value);
    }
     
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setActiveFromAttribute($value)
    {
        if (empty($value)) $this->attributes['active_from'] = null;
        else $this->attributes['active_from'] = Carbon::parse($value);
    }
    
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setActiveToAttribute($value)
    {
        if (empty($value)) $this->attributes['active_to'] = null;
        else $this->attributes['active_to'] = Carbon::parse($value);
    }
    
    /**
     * Set the password hash
     *
     * @param  string  $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    
    public function countMandants(){
       return $this->hasMany('App\MandantUser','user_id','id');
    }
    
    public function mandantUsers(){
       return $this->hasMany('App\MandantUser','user_id','id');
    }
    
    // public function mandantUserRoles(){
    //   return $this->hasMany('App\MandantUserRole','user_id','id');
    // }
    
    public function mandantUsersDistinct(){
       return $this->hasMany('App\MandantUser','user_id','id')->groupBy('mandant_id');
    }
    
    public function mandantRoles(){
       return $this->hasManyThrough('App\MandantUserRole','App\MandantUser');
    }
   
    public function is($roleName)
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == $roleName)
            {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Searches a user by their first and lastname
     * 
     * This function tries to find users by their name.
     * It will check Firstname and Lastname OR Lastname and Firstname
     * For example 
     *          ['first_name' = 'Foo', 'last_name' => 'Bar']
     *          OR
     *          ['first_name' = 'Bar', 'last_name' => 'Foo']
     * 
     * @param string $real_user_name A string with the real name
     * @return User|null User object if found, null on fail
     */
    public static function findByName($real_user_name){
        $parts = explode(' ', $real_user_name, 2);
        if(count($parts) == 2){
            $user = self::where('first_name', $parts[0])
                        ->where('last_name', $parts[1])
                        ->orWhere(function($query) use ($parts) {
                            $query->where('first_name', $parts[1])
                                  ->where('last_name', $parts[0]);
                        })
                        ->first();
            return $user;
        }else{
            return null;
        }
    }
}
