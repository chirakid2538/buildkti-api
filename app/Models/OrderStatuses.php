<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    protected $fillable = ['state' , 'state_text'];
}