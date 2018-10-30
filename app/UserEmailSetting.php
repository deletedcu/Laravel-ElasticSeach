<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmailSetting extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = [];
    protected $fillable = ['user_id', 'document_type_id', 'email_recievers_id', 'sending_method', 'recievers_text', 'active'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
