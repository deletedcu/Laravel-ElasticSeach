<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventorySize extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','active']; //whitelist
    
    protected $dates = ['created_at', 'updated_at'];
    
     public function items(){
        return $this->hasMany(Inventory::class,'inventory_size_id');
    }
}
