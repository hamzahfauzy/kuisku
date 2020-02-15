<?php
namespace App\Controllers\Master;
use User;

class UserController
{
    function index()
    {
        return User::whereNotIn('id',[session()->get('id')])->get();
    }

    function find($id)
    {
        return User::find($id);
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'user_name'   => ['required'],
                'user_email'  => ['required'],
                'user_pass'   => ['required'],
            ];
            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $user = new User;
                $user->save([
                    'user_name'   => $request->user_name,
                    'user_email'  => $request->user_email,
                    'user_login'  => $request->user_email,
                    'user_pass'   => md5($request->user_pass),
                    'user_level'  => $request->user_level,
                    'user_status' => 1,
                ]);
                return $this->index();
            }
        }

        return ['status' => false];
    }

    function update()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'user_name'   => ['required'],
                'user_email'  => ['required','unique|User'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $user = User::find($request->id);
                $password = !empty($request->user_pass) ? md5($request->user_pass) : $user->getPassword();
                $user->save([
                    'user_name'   => $request->user_name,
                    'user_email'  => $request->user_email,
                    'user_login'  => $request->user_email,
                    'user_pass'   => $password,
                ]);

                return $this->index();
            }
            
        }

        return ['status' => false];
    }

    function delete()
    {
        $request = request()->post();
        if($request)
        {
            User::delete($request->id);
            return $this->index();
        }

        return ['status' => false];
    }

}
