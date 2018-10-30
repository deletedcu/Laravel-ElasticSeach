<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WikiPage extends Model
{
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'user_id', 'status_id', 'category_id', 'name', 'subject',  'content', 'allow_all'
    ]; //whitelist
     
    public function getDateExpiredAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function category(){
        return $this->belongsTo('App\WikiCategory', 'category_id', 'id');
    }
    
    public function status(){
        return $this->belongsTo('App\WikiPageStatus', 'status_id', 'id');
    }
    
    public function histories(){
        return $this->hasMany('App\WikiPageHistory', 'wiki_page_id', 'id')->orderBy('wiki_page_histories.updated_at','desc');
    }
}
