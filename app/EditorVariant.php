<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditorVariant extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','variant_number','inhalt','approval_all_mandants']; //whitelist

    public function document(){
        return $this->belongsTo('App\Document');
    }

    public function documentUpload(){
        return $this->hasMany('App\DocumentUpload');
    }

    public function documentUploadTrashed(){
        return $this->hasMany('App\DocumentUpload')->onlyTrashed();
    }

    public function editorVariantDocument(){
        return $this->hasMany('App\EditorVariantDocument');
    }

    public function editorVariantDocumentTrashed(){
        return $this->hasMany('App\EditorVariantDocument')->onlyTrashed();
    }
    
    public function documentMandants(){
        return $this->hasMany('App\DocumentMandant','editor_variant_id','id') ;
    } 
 
    
    public function documentMandant(){
        return $this->hasMany('App\DocumentMandant');
    }
    
    public function documentMandantRoles(){
        return $this->hasManyThrough('App\DocumentMandantRole','App\DocumentMandant') ;
    }
    
    public function documentMandantMandants(){
        return $this->hasManyThrough('App\DocumentMandantMandant','App\DocumentMandant') ;
    }
}
