<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageGroups extends Model
{
    
    public function images()
    {
        return $this->hasMany('App\Models\Images' , 'image_group_id');
    }
}