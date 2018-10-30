<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentMandantMandant extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['document_mandant_id','mandant_id',]; //whitelist
    
    public function documentMandant(){
       return $this->belongsTo('App\DocumentMandant') ;
    }
    
    public function mandant(){
       return $this->belongsTo('App\Mandant') ;
    }
}