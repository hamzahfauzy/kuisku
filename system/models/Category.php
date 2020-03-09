<?php

class Category extends Model {

    function posts()
    {
        $category_post = $this->hasMany(CategoryPost::class,['category_id'=>'id']);

        $return = [];
        foreach($category_post as $pivot)
        {
            $pivot->post();
            $return[] = $pivot;
        }

        return $return;
    }

    function user()
    {
        return $this->hasOne(CategoryUser::class,['category_id'=>'id']);
    }

}