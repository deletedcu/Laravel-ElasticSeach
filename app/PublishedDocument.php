<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublishedDocument extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','document_group_id','url_unique']; //whitelist
    
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:m:s');
    }
        
    public function document(){
        return $this->belongsTo('App\Document');
    }
    
}
