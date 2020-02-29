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
        $user = User::where('user_login',$request->user_login)->where('user_pass',$password)->where('user_status',1)->first();
        if(empty($user) || $user == null)
        {
            session()->set('error','Username atau Password salah');
            session()->set('old_email',$request->user_login);
            return route('login');
        }

        if($user->user_level == 'admin' && !$user->customer())
        {
            session()->set('error','Akun anda sedang tidak aktif.');
            session()->set('old_email',$request->user_login);
            return route('login');
        }

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