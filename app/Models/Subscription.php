<?php
namespace App\Models;
use Model;

class Subscription extends Model
{
    
    function product()
    {
        return $this->hasOne(Product::class,['id'=>'product_id']);
    }

    function customer()
    {
        return $this->hasOne(Customer::class,['id','customer_id']);
    }
}