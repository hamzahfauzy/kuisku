<?php
namespace App\Models;
use Model;

class CategoryKuis extends Model {

    static $table = "category_kuis";

    function category()
    {
        return $this->hasOne(Category::class, ['id'=>'category_id']);
    }

    function kuis()
    {
        return $this->hasOne(Post::class, ['id'=>'kuis_id']);
    }

}