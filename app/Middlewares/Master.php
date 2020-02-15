<?php
namespace App\Middlewares;

class Master
{
    function __construct()
    {
        if(!session()->get('id') || session()->user()->user_level != 'master')
        {
            session()->destroy();
            redirect(base_url().'/login');
            return;
        }

        return;
    }
}