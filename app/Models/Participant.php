<?php
namespace App\Models;
use User;

class Participant extends User
{
    static $table = "users";
    public $user_level;

    function sesi()
    {
        return $this->hasMany(SesiUser::class,['user_id'=>'id']);

        $return = [];
        foreach($sesi_users as $pivot)
        {
            $pivot->sesi();
            $return[] = $pivot;
        }

        return $return;
    }
}