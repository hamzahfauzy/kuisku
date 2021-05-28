<?php

class Post extends Model {

    function meta($key = false)
    {
        if(!$key)
        {
            $metas = $this->hasMany(PostMeta::class,['post_id' => 'id']);
            $return = [];
            foreach($metas as $meta)
                $return[$meta->meta_key] = $meta->meta_value;

            return $return;
        }

        $post_meta = PostMeta::where('post_id',$this->id)->where('meta_key',$key)->first();
        if($post_meta)
            return $post_meta->meta_value;
        return 0;
    }

    function comment($id = false)
    {
        if(!$id)
            return $this->hasMany(Comment::class,['post_id'=>'id']);

        return Comment::where('post_id',$this->id)->where('id',$id)->first();
    }

    function categories()
    {
        $category_post = $this->hasMany(CategoryPost::class,['post_id'=>'id']);

        $return = [];
        foreach($category_post as $pivot)
        {
            $pivot->category();
            $return[] = $pivot;
        }

        return $return;
    }

}