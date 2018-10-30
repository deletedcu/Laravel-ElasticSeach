<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    const NEWS = 1;
    const RUNDSCHREBIEN = 2;
    const QM_RUNDSCHREBIEN = 3;
    const ISO_DOKUMENTE = 4;
    const FORMULARE = 5;
    const ANLAGEN = 6;
    const JURISTEN = 7;
    const NOTIZEN = 8;
    const BERATUNG = 9;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['name','document_art','document_role','read_required','allow_comments','order_number', 'menu_position', 'jurist_document', 'publish_sending']; //whitelist

    public function documents(){
        return $this->hasMany('App\Document');
    }
    
}
