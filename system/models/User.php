<?php

class User extends Model
{
    
    function meta($param = false)
    {
        if(!$param)
        {

            $metas = $this->hasMany(UserMeta::class,['user_id' => 'id']);
            $return = [];
            foreach($metas as $meta)
                $return[$meta->meta_key] = $meta->meta_value;

            return $return;
        }

        return UserMeta::where('user_id',$this->id)->where('meta_key',$param)->first()->meta_value;
    }
}