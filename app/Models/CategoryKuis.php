<?php
namespace App\Models;
use Model;
use Category;
use CategoryPost;

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

    function soal()
    {
        $categorySoal = $this->hasMany(CategoryPost::class,['category_id'=>'category_id']);
        $data = [];
        foreach($categorySoal as $cat)
            $data[] = $cat->post();

        return $data;

    }

}