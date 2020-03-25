<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomProducts extends Model
{
    
    public function customItems()
    {
        return $this->hasMany('App\Models\CustomItems' , 'custom_product_id');
    }

    public function customGroups()
    {
        return $this->belongsTo('App\Models\CustomGroups', 'custom_group_id');
    }
}