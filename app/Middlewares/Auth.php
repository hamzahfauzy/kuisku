<?php
namespace App\Middlewares;

class Auth
{
    function __construct()
    {
        if(!session()->get('id'))
        {
            session()->destroy();
            redirect(base_url().'/login');
            return;
        }

        redirect(base_url().'/'.session()->user()->user_level);
        return;
    }
}