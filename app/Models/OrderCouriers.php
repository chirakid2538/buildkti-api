<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCouriers extends Model
{
    protected $fillable = ["courier_id","code","tracking_code","courier_tracking_code","cost","price","state","datetime_booking","datetime_confirm"];
}