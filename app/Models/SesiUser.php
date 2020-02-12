<?php
namespace App\Models;
use User;
use Model;

class SesiUser extends Model
{
    static $table = 'sesi_users';
    function user()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    function sesi()
    {
        return $this->hasOne(Sesi::class,['id'=>'post_id']);
    }

    function partSesi()
    {
        return $this->hasOne(ParticipantSession::class,['post_exam_id'=>'post_id','user_id'=>'user_id']);
    }
}