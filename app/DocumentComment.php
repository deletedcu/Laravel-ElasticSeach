<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentComment extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['user_id','document_id','freigeber','betreff','comment','active', 'approved']; //whitelist
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:i:s');
    }
    
    public function getCommentAttribute($value)
    {
        return str_replace(["\r\n", "\r", "\n"], "<br/>", $value);
    }
     
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    
    public function published(){
        return $this->hasOne('App\DocumentApproval','document_id','document_id')->where('document_approvals.user_id',$this->user_id);
    }
    
    public function document(){
        return $this->hasOne('App\Document','id','document_id');
    }
}