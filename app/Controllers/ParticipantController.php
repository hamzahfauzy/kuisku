<?php
namespace App\Controllers;
use App\Models\Participant;

class ParticipantController
{
    function index()
    {
        return Participant::get();
    }

    function find($id)
    {
        return Participant::where('id',$id)->first();
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
                $participant = new Participant;
                $participant->save([
                    'user_name'   => $request->user_name,
                    'user_email'  => $request->user_email,
                    'user_login'  => $request->user_email,
                    'user_pass'   => md5($request->user_pass),
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
                $participant = Participant::find($request->id);
                $password = !empty($request->user_pass) ? md5($request->user_pass) : $participant->user_pass;
                $participant->save([
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
            Participant::delete($request->id);
            return $this->index();
        }

        return ['status' => false];
    }

}
