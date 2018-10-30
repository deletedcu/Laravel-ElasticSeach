<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MandantInventoryAccounting extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['inventory_id','mandant_id','inventory_category_id','inventory_size_id','value',
    'sell_price','accounted_for']; //whitelist
    
    public function item(){
        return $this->hasOne(Inventory::class,'id','inventory_id');
    }
    
    public function mandant(){
        return $this->hasOne(Mandant::class,'id','mandant_id');
    }
    
     public function category(){
        return $this->hasOne(InventoryCategory::class,'id','inventory_category_id');
    }
    
    public function size(){
        return $this->hasOne(InventorySize::class,'id','inventory_size_id');
    }
   
}
