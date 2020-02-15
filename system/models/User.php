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

        return UserMeta::where('user_id',$this->id)->where('meta_key',$param)->first()->meta_value;
    }

    function customer()
    {
        return $this->hasOne(CustomerUser::class,['user_id'=>'id'])->customer();
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