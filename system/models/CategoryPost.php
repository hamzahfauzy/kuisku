<?php

class CategoryPost extends Model {

    static $table = "category_post";

    function category()
    {
        return $this->hasOne(Category::class, ['id'=>'category_id']);
    }

    function post()
    {
        return $this->hasOne(Post::class, ['id'=>'post_id']);
    }

}