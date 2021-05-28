<?php
use App\Models\CustomerUser;

class User extends Model
{

    protected $user_pass;
    
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

        $user_meta = UserMeta::where('user_id',$this->id)->where('meta_key',$param)->first();
        if($user_meta)
            return $user_meta->meta_value;
        return '';
    }

    function customer()
    {
        $hasOne = $this->hasOne(CustomerUser::class,['user_id'=>'id']);
        if($hasOne)
            return $hasOne->customer();
        return 0;
    }

    function getPassword()
    {
        return $this->user_pass;
    }

    function setPassword($password)
    {
        $this->user_pass = md5($password);
    }
}