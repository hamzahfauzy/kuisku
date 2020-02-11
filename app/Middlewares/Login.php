<?php
namespace App\Middlewares;

class Login
{
    function __construct()
    {
        if(session()->get('id'))
            redirect(base_url().'/');
        return;
    }
}