<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentMandant extends Model
{
    use SoftDeletes;
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','editor_variant_id']; //whitelist
    
    public function documentMandantMandants(){
        return $this->hasMany('App\DocumentMandantMandant','document_mandant_id','id') ;
    } 
    public function documentMandantRole(){
        return $this->hasMany('App\DocumentMandantRole','document_mandant_id','id') ;
    } 
    public function editorVariant(){
        return $this->belongsTo('App\EditorVariant','editor_variant_id','id') ;
    } 
}

