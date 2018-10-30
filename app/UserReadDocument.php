<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReadDocument extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_group_id','user_id','date_read', 'date_read_last']; //whitelist
    
    public function user(){
       return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
