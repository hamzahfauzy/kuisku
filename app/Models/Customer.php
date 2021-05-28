<?php
namespace App\Models;
use Model;

class Customer extends Model
{

    function participants()
    {
        $participants = $this->hasMany(CustomerParticipant::class,['customer_id'=>'id']);
        $return = [];
        foreach($participants as $participant)
        {
            $part = $participant->participant();
            $pivot = CustomerParticipant::find($participant->id);
            $part->pivot = $pivot;
            $return[] = $part;
        }

        return $return;
    }

    function users()
    {
        $users = $this->hasMany(CustomerUser::class,['customer_id'=>'id']);
        $return = [];
        foreach($users as $user)
        {
            $usr = $user->user();
            $pivot = CustomerUser::find($user->id);
            $usr->pivot = $pivot;
            $return[] = $usr;
        }

        return $return;
    }

    function logo()
    {
        return $this->hasOne(CustomerLogo::class,['customer_id'=>'id']);
    }

    function subscriptions()
    {
        return $this->hasMany(Subscription::class,['customer_id'=>'id']);
    }

    function subscription_active()
    {
        return Subscription::where('customer_id',$this->id)->where('status',1)->first();
    }
}