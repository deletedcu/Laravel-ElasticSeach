<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Inventory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','inventory_category_id','inventory_size_id','value', 'min_stock','sell_price',
    'purchase_price', 'neptun_intern']; //whitelist
    
    protected $dates = ['created_at', 'updated_at'];
    
    public function category(){
        return $this->hasOne(InventoryCategory::class,'id','inventory_category_id');
    }
    
    public function size(){
        return $this->hasOne(InventorySize::class,'id','inventory_size_id');
    }
    
    public function history(){
        return $this->hasMany(InventoryHistory::class,'inventory_id')->orderBy('inventory_histories.created_at','desc')->take(20);
    }
    
    public function getUpdatedAtAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
            return Carbon::parse($value)->format('d.m.Y H:i:s');
    }
}
