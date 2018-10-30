<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\ApprovalDocument;

class Document extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'document_type_id', 'document_status_id', 'user_id','date_created','version',
        'name','name_long','owner_user_id','search_tags',
        'summary','date_published','published_at','date_modified','date_expired',
        'version_parent','document_group_id','iso_category_id',
        'show_name','adressat_id','betreff','document_replaced_id',
        'date_approved','email_approval','approval_all_roles', 'document_template',
        'approval_all_mandants','pdf_upload','is_attachment','active','iso_category_number',
        'qmr_number','landscape','additional_letter','jurist_category_id','beratung_category_id',
        'jurist_log_text','jurist_category_meta_id','jurist_log_text','funktion','nachricht','telefon','ruckruf',
        'note_date','note_time','client','mandant_id',
    ]; //whitelist
    
    
    protected $dates = ['created_at', 'updated_at', 'date_published','published_at','note_date'];
     
    public function getDatePublishedAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
            return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function setDatePublishedAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            $this->attributes['date_published'] = null;
        else
            $this->attributes['date_published'] = Carbon::parse($value);
    }
    
    public function getNoteDateAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
        // Carbon\Carbon::createFromFormat('H:i', $value);
            return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function setNoteDateAttribute($value)
    {
        if(empty($value) || $value == null || $value == ''){
            $this->attributes['note_date'] = null;
        }
        else{
        
            $this->attributes['note_date'] = Carbon::parse( $value );
        }
    }
    
    public function getNoteTimeAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
        // Carbon\Carbon::createFromFormat('H:i', $value);
            return Carbon::parse($value)->format('H:i');
    }
    
    public function setNoteTimeAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            $this->attributes['note_time'] = null;
        else{
            $this->attributes['note_time'] = Carbon::parse( $value )->format('H:i:s');
        }
    }
    
    public function getPublishedAtAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
            return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function setPublishedAtAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            $this->attributes['published_at'] = null;
        else
            $this->attributes['published_at'] = Carbon::parse($value);
    }
    
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:m:s');
    }
    
    public function setDateExpiredAttribute($value)
    {
        
         if(empty($value) || $value == null || $value == '')
            $this->attributes['date_expired'] = null;
        else
            $this->attributes['date_expired'] = Carbon::parse($value);
            
    }
    public function getDateExpiredAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
            return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function getNameLongAttribute($value)
    {
            return str_replace(["\r\n", "\r", "\n"], "<br/>", $value);
    }
    
    public function getBetreffAttribute($value)
    {
            return str_replace(["\r\n", "\r", "\n"], "<br/>", $value);
    }
    
    // public function getCreatedAtAttribute($value)
    // {
    //     return $this->attributes['created_at'] = Carbon::parse($value)->format('d.m.Y');
    // }
    
    // public function setCreatedAtAttribute($value)
    // {
    //     $this->attributes['created_at'] = Carbon::now();
    // }
    
    public function setAdressatIdAttribute($value){
        if($value == null)
            $this->attributes['adressat_id'] = null; 
        elseif($value == "null" || empty($value) )
            $this->attributes['adressat_id'] = null; 
        else
            $this->attributes['adressat_id'] = $value;
    }
    
    public function setIsoCategoryIdAttribute($value){
        if($value == null)
            $this->attributes['iso_category_id'] = null; 
        elseif($value == "null" || empty($value) )
             $this->attributes['iso_category_id'] = null; 
        else
            $this->attributes['iso_category_id'] = $value;
    }
    
    public function documentType(){
        return $this->belongsTo('App\DocumentType');
    }
    
    public function documentStatus(){
        return $this->belongsTo('App\DocumentStatus');
    }
    
    public function documentAdressats(){
        return $this->belongsTo('App\Adressat','adressat_id');
    }
    
    public function published(){
        return $this->hasOne('App\PublishedDocument','document_id','id');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function owner(){
        return $this->belongsTo('App\User', 'owner_user_id', 'id');
    }
    
    public function isoCategories(){
        return $this->belongsTo('App\IsoCategory', 'iso_category_id', 'id');
    }
    public function isoCategoriesWhereSlug($slug){
        return $this->belongsToMany('App\IsoCategory', 'iso_category_id', 'id')->where('slug',$slug);
    }
    
    public function editorVariant(){
        return $this->hasMany('App\EditorVariant');
    }
    public function editorVariantNoDeleted(){
        return $this->hasMany('App\EditorVariant')->where('deleted_at',null);
    }
    public function editorVariantTrashed(){
        return $this->hasMany('App\EditorVariant')->onlyTrashed();
    }
    public function editorVariantOrderBy(){
        return $this->hasMany('App\EditorVariant')->orderBy('variant_number');
    }
    
    public function editorVariantDocument(){
        return $this->hasManyThrough('App\EditorVariantDocument','App\EditorVariant');
    }
    /*
     * Get All documents where default document is an attachment   
     */
    public function variantDocuments(){
        return $this->hasMany('App\EditorVariantDocument','document_id','id');
    }
    public function lastEditorVariant(){
            return $this->hasMany('App\EditorVariant')->orderBy('variant_number','desc')->take(1);
    }
    
    public function documentApprovals(){
        return $this->hasMany('App\DocumentApproval');
    }
    
    public function documentApprovalFreigeber(){
        return $this->hasOne('App\DocumentApproval')->where('user_id', Auth::user()->id) ;
    }
    public function documentApprovalsApprovedDateNotNull(){
        return $this->hasMany('App\DocumentApproval')->whereNotNull('date_approved');
    }
    
    public function documentMandants(){
        return $this->hasManyThrough('App\DocumentMandant','App\EditorVariant') ;
    }
    
    public function documentMandantMandants(){
        return $this->hasManyThrough('App\DocumentMandantMandant','App\DocumentMandant','App\EditorVariant') ;
    }
    
    public function documentMandantRoles(){
        return $this->hasManyThrough('App\DocumentMandantRole','App\DocumentMandant','App\EditorVariant') ;
    }
    
    public function documentCoauthors(){
        return $this->hasManyThrough('App\DocumentCoauthor','App\User') ;
    }
    public function documentCoauthor(){
        return $this->hasOne('App\DocumentCoauthor','document_id', 'id') ;
    }
    
    public function documentUploads(){
        return $this->hasManyThrough('App\DocumentUpload','App\EditorVariant') ;
    }
    
    public function publishedDocuments(){
        return $this->hasMany('App\PublishedDocument','document_id','id');
    }
    
    public function documentHistory(){
        return $this->hasMany('App\Document','document_group_id','document_group_id');
    }
    
}
