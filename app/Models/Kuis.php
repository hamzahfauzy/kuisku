<?php
namespace App\Models;
use Post;

class Kuis extends Post
{
    static $table = "posts";
    public $post_type;

    function sesi()
    {
        return $this->hasMany(Sesi::class,['post_parent_id'=>'id']);
    }

    function soal()
    {
        $soal = $this->hasMany(ExamQuestion::class,['post_exam_id'=>'id']);
        $return = [];
        foreach($soal as $pivot)
        {
            $pivot->soal();
            $return[] = $pivot;
        }

        return $return;
    }
    
}