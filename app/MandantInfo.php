<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantInfo extends Model
{
    use SoftDeletes;
    
    //
    
    // public function getErlaubnissGultigAbAttribute($value)
    // {
    //     if (empty($value)) return null;
    //     else return Carbon::parse($value)->format('d.m.Y');
    // }

    // public function setErlaubnissGultigAbAttribute($value)
    // {
    //     if (empty($value)) $this->attributes['erlaubniss_gultig_ab'] = null;
    //     else $this->attributes['erlaubniss_gultig_ab'] = Carbon::parse($value);
    // }
    
    // public function getBefristetBisAttribute($value)
    // {
    //     if (empty($value)) return null;
    //     else return Carbon::parse($value)->format('d.m.Y');
    // }

    // public function setBefristetBisAttribute($value)
    // {
    //     if (empty($value)) $this->attributes['befristet_bis'] = null;
    //     else $this->attributes['befristet_bis'] = Carbon::parse($value);
    // }
    
    protected $guarded = []; //blacklist
    protected $fillable = 
        [
            'mandant_id','prokura','betriebsnummmer','handelsregister',
            'handelsregister_sitz','steuernummer','ust_ident_number','zausatzinfo_steuer',
            'berufsgenossenschaft_number','berufsgenossenschaft_zusatzinfo','erlaubniss_gultig_ab','erlaubniss_gultig_von',
            'geschaftsjahr','geschaftsjahr_info','bankverbindungen','info_wichtiges','info_sonstiges',
            'steuernummer_lohn','mitarbeiter_finanz_id','mitarbeiter_edv_id','mitarbeiter_vertrieb_id','mitarbeiter_umwelt_id', 
            'angemeldet_am', 'umgemeldet_am', 'abgemeldet_am', 'gewerbeanmeldung_history', 'unbefristet', 'befristet_bis', 'erlaubnisverfahren',
        ]; //whitelist
        
    public function mandant(){
        return $this->belongsTo('App\Mandant');
    }
}
 