<?php
namespace App\Models;
use Model;

class ExamQuestion extends Model
{
    static $table = 'exam_questions';
    function kuis()
    {
        return $this->hasOne(Kuis::class,['id'=>'post_exam_id']);
    }

    function soal()
    {
        return $this->hasOne(Soal::class,['id'=>'post_question_id']);
    }
}