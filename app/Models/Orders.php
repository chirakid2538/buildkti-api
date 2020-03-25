<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public function orderCustoms()
    {
        return $this->hasMany( OrderCustoms::class , 'order_id' );
    }
    public function orderStatuses()
    {
        return $this->hasMany( OrderStatuses::class , 'order_id' );
    }
    public function orderCouriers()
    {
        return $this->hasOne( OrderCouriers::class , 'order_id' );
    }

    public function orderReceivers()
    {
        return $this->hasOne( OrderReceivers::class , 'order_id' );
    }
    public function orderSenders()
    {
        return $this->hasOne( OrderSenders::class , 'order_id' );
    }
}