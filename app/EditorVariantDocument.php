<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditorVariantDocument extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['editor_variant_id','document_status_id','document_group_id','document_id']; //whitelist
    
    public function document(){
        return $this->belongsTo('App\Document', 'document_id', 'id');
    }
    public function editorVariant(){
        return $this->belongsTo('App\EditorVariant', 'editor_variant_id', 'id');
    }
}
