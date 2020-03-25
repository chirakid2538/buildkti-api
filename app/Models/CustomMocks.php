<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMocks extends Model
{
    
    public function customItems()
    {
        return $this->belongsTo('App\Models\CustomItems' , 'custom_item_id');
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