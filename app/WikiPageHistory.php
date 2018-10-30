<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WikiPageHistory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = [ 'wiki_page_id', 'user_id' ]; //whitelist
    
     public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('d.m.Y H:i:s');
    }
    
     public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('d.m.Y H:i:s');
    }
    
     public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
  
}
