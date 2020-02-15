<?php
namespace App\Controllers;
use User;
use Page;
use Category;
use App\Models\{Soal,Kuis,Participant};

class HomeController
{

    function index()
    {
        if(session()->user()->user_level == 'master')
        {

            $soal = Soal::count();
            $kuis = Kuis::count();
            $peserta = Participant::count();
            $kategori = Category::count();
            $total = $soal+$kuis+$peserta+$kategori;
            return [
                'soal' => $soal, 
                'kuis' => $kuis,
                'peserta' => $peserta,
                'kategori' => $kategori,
                'total' => $total
            ];
        }
        elseif(session()->user()->user_level == 'admin')
        {
            $user = session()->user();
            $customer = $user->customer();
            $soal = Soal::where('post_author_id',$user->id)->count();
            $kuis = Kuis::where('post_author_id',$user->id)->count();
            $peserta = count($customer->participants());
            $kategori = Category::count();
            $total = $soal+$kuis+$peserta+$kategori;
            return [
                'soal' => $soal, 
                'kuis' => $kuis,
                'peserta' => $peserta,
                'kategori' => $kategori,
                'total' => $total
            ];
        }
        return [
            'soal' => 0, 
            'kuis' => 0,
            'peserta' => 0,
            'kategori' => 0,
            'total' => 0
        ];
    }

    function setting()
    {
        return ['user'=>session()->user()];
    }

    function update()
    {
        $request = request()->post();
        $user = session()->user();
        $password = !empty($request->password) ? md5($request->password) : $user->getPassword();
        $user->save([
            'user_name'   => $request->nama,
            'user_email'  => $request->email,
            'user_login'  => $request->email,
            'user_pass'   => $password,
        ]);

        return route('admin/setting');
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