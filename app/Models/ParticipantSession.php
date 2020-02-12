<?php
namespace App\Models;
use User;
use Model;

class ParticipantSession extends Model
{
    static $table = 'participant_session';
    function user()
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    function sesi()
    {
        return $this->hasOne(Sesi::class,['id'=>'post_exam_id']);
    }
}