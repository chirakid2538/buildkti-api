<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomGroups extends Model
{
    
    public function customProducts()
    {
        return $this->hasMany('App\Models\CustomProducts' , 'custom_group_id');
    }
    public function customCanvases()
    {
        return $this->hasOne( CustomCanvases::class , 'custom_group_id');
    }
}