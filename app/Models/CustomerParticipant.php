<?php
namespace App\Models;
use Model;

class CustomerParticipant extends Model
{
    static $table = 'customer_participant';

    function participant()
    {
        return $this->hasOne(Participant::class,['id'=>'participant_id']);
    }
}