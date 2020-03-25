<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustoms extends Model
{
    protected $fillable = ['custom_group_id', 'custom_product_id', 'custom_item_id', 'cost', 'discount', 'price','amount'];
    
    public function orderCustomMocks()
    {
        return $this->hasMany( OrderCustomMocks::class , 'order_custom_id' );
    }
}