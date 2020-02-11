<?php
namespace App\Controllers;
use User;

class AuthController
{

    function login()
    {
        if(session()->get('id'))
            redirect(history()->back());
        
        return;
    }

    function dologin()
    {
        if(session()->get('id'))
            redirect(history()->back());
            
        $request = request()->post();
        $password = md5($request->user_pass);
        $user = User::where('user_login',$request->user_login)->where('user_pass',$password)->first();
        if(empty($user) || $user == null)
            return history()->back();

        session()->set('id',$user->id);
        return route('/');
        
    }

    function logout()
    {
        if(session()->get('id'))
            session()->destroy();
        
        return;
    }

}