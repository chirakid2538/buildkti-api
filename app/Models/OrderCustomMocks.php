<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomMocks extends Model
{
    protected $fillable = ['order_custom_id', 'custom_mock_id', 'cost', 'discount', 'price', 'image', 'image_original'];
}