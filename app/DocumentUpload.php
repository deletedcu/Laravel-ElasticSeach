<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentUpload extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['editor_variant_id','file_path']; //whitelist
    
    public function editorVariant(){
        return $this->belongsTo('App\EditorVariant');
    }
}
