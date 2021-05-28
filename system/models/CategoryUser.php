<?php

class CategoryUser extends Model {

    static $table = "category_user";

    function category()
    {
        return $this->hasOne(Category::class, ['id'=>'category_id']);
    }

    function user()
    {
        return $this->hasOne(User::class, ['id'=>'user_id']);
    }

}