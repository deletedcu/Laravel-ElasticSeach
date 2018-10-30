<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSentDocument extends Model
{
    protected $fillable = ['user_email_setting_id', 'document_id', 'sent'];
    
    public function userEmailSetting(){
       return $this->belongsTo('App\UserEmailSetting', 'user_email_setting_id', 'id');
    }
}