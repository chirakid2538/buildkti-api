<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReceivers extends Model
{
    protected $fillable = [ 'order_id', 'name', 'address', 'subDistrict', 'district', 'province', 'postcode', 'phone', 'email' ];
}