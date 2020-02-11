<?php
namespace App\Middlewares;

class Participant
{
    function __construct()
    {
        if(!session()->get('id') || session()->user()->user_level != 'participant')
        {
            session()->destroy();
            redirect(base_url().'/login');
            return;
        }

        return;
    }
}