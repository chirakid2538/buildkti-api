<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomItems extends Model
{
    
    public function customMocks()
    {
        return $this->hasMany('App\Models\CustomMocks' , 'custom_item_id');
    }

    public function customGroups()
    {
        return $this->belongsTo('App\Models\CustomGroups', 'custom_group_id');
    }

    public function customProducts()
    {
        return $this->belongsTo('App\Models\CustomProducts', 'custom_product_id');
    }
}