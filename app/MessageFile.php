<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageFile extends Model
{
    protected $fillable = ['contact_message_id', 'filename'];
    
    public function message(){
        return $this->belongsTo('App\ContactMessage','contact_message_id');
    }
}