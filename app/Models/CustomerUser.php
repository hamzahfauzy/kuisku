<?php
namespace App\Models;
use Model;
use User;

class CustomerUser extends Model
{
    static $table = 'customer_user';

    function user()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    function customer()
    {
        return $this->hasOne(Customer::class,['id'=>'customer_id']);
    }
}