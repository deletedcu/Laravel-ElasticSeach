<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentApproval extends Model
{
    //use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['user_id','document_id','date_approved','approved', 'fast_published']; //whitelist
    
    public function getDateApprovedAttribute($value)
    {
        if( $value == null){
            return null;
        }
        else{
            return Carbon::parse($value)->format('d.m.Y H:i:s');
        } 
    }
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
}
