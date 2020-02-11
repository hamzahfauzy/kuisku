<?php
namespace App\Models;
use Post;
use App\Models\Jawaban;

class Soal extends Post 
{
    static $table = 'posts';
    public $post_type;

    function answers()
    {
        return $this->hasMany(Jawaban::class,['post_parent_id'=>'id']);
    }
}