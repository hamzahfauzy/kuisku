<?php
namespace App\Middlewares;

class Admin
{
    function __construct()
    {
        if(!session()->get('id') || session()->user()->user_level != 'admin')
        {
            session()->destroy();
            redirect(base_url().'/login');
            return;
        }

        return;
    }
}