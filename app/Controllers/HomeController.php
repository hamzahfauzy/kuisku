<?php
namespace App\Controllers;
use User;
use Page;

class HomeController
{

    function index()
    {
        return ['name' => 'Adam', 'address' => 'Washington DC'];
    }

    function profile()
    {
        if(isset(request()->get()->name))
        {
            $users = User::where('nama',request()->get()->name)->first();
        }
        else
        {
            $users = User::get();
        }
        return ['users' => $users];
    }

    function simpan()
    {
        $user = new User;
        $user->save([
            'user_name' => 'Pengguna',
            'user_login' => 'Pengguna',
            'user_pass' => md5('pengguna'),
            'user_email' => 'pengguna@mail.com',
            'user_status' => 1

        ]);
        return route('/');
    }

    function data()
    {
        $user = User::where('id',session()->get('id'))->first();
        if($user)
        {
            $user->meta = $user->meta();
            return $user;
        }

        return [];

        // $user->alamat = $user->meta('alamat');
        // $user->no_telepon = $user->meta('no_telepon');
    }

    function testPage()
    {
        $page = Page::get();
        print_r($page);
    }

}