<?php
namespace App\Models;
use Post;

class Sesi extends Post
{
    static $table = "posts";
    public $post_type;

    function peserta()
    {
        $sesi_users = $this->hasMany(SesiUser::class,['post_id'=>'id']);

        $return = [];
        foreach($sesi_users as $pivot)
        {
            $pivot->user();
            $return[] = $pivot;
        }

        return $return;
    }

    function kuis()
    {
        return $this->hasOne(Kuis::class,['id'=>'post_parent_id']);
    }
}