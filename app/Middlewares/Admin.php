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

        if(!session()->user()->customer())
        {
            // session()->set('error','Akun anda sedang tidak aktif.');
            setcookie('error','Akun anda sedang tidak aktif.', time() + (86400 * 30), '/');
            session()->destroy();
            redirect(base_url().'/login');
            return;
        }

        return;
    }
}