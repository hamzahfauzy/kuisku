<?php
namespace App\Controllers;
use User;
use Page;
use Category;
use CategoryUser;
use App\Models\{Soal,Kuis,Participant,CustomerLogo};

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
            $kategori = CategoryUser::where('user_id',$user->id)->count();
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
            'user_pass'   => $password,
        ]);

        return route('admin/setting');
    }

    function upload()
    {
        if(isset($_FILES['file']['name']))
        {
            $file      = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];
            $file_name_array = explode(".", $file_name);
            $extension = end($file_name_array);
            $new_image_name  = time() . "" . rand() . '.' . $extension;
            chmod('uploads', 0777);
            $allowed_extension = array("jpg", "gif", "png");
            if(in_array($extension, $allowed_extension))
            {
                move_uploaded_file($file, 'uploads/' . $new_image_name);
                $file_url = base_url().'/uploads/' . $new_image_name;
                
                $user     = session()->user();
                $customer = session()->user()->customer();
                $logo     = CustomerLogo::where('customer_id',$customer->id)->first();
                if($logo)
                {
                    $logo->save([
                        'file_url'    => $file_url
                    ]);
                }
                else
                {

                    $logo    = new CustomerLogo;
                    $logo->save([
                        'customer_id' => $customer->id,
                        'file_url'    => $file_url
                    ]);
                }
                        
                return ['status'=>true,'logo' => $file_url];
            }
        }
        
        return ['status' => false];
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