<?php
namespace App\Controllers;
use User;
use App\Models\{CustomerParticipant, Participant};

class ParticipantController
{
    function index()
    {
        $customer = session()->user()->customer();
        return $customer->participants();
    }

    function find($id)
    {
        $customer = session()->user()->customer();
        $customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->where('participant_id',$id)->first();
        return $customerParticipant->participant();
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $customer = session()->user()->customer();

            $validate = [
                'user_name'   => ['required'],
                'user_email'  => ['required'],
                'user_pass'   => ['required'],
            ];

            $data = (array) $request;
            $user_checker = User::where('user_email',$request->user_email)->first();
            if($user_checker)
                return ['status' => false,'msg'=>'Username exists with different role'];
            $_participant = Participant::where('user_email',$request->user_email)->first();
            if(!$_participant)
                if(count(request()->validate($data, $validate)) == 0)
                {
                    $participant = new Participant;
                    $participant_id = $participant->save([
                        'user_name'   => $request->user_name,
                        'user_email'  => $request->user_email,
                        'user_login'  => $request->user_email,
                        'user_pass'   => md5($request->user_pass),
                        'user_status' => 1,

                    ]);

                    $customerParticipant = new CustomerParticipant;
                    $customerParticipant->save([
                        'customer_id' => $customer->id,
                        'participant_id' => $participant_id
                    ]);

                    return $this->index();

                }
                else
                    return ['status' => false];
            else
            {
                $participant_id = $_participant->id;
                $_customerParticipant = CustomerParticipant::where('customer_id',$customerParticipant->id)->where('participant_id',$participant_id)->first();
                if($_customerParticipant)
                    return ['status' => false];
                else
                {
                    $customerParticipant = new CustomerParticipant;
                    $customerParticipant->save([
                        'customer_id' => $customer->id,
                        'participant_id' => $participant_id
                    ]);

                    return $this->index();
                }
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
                $password = !empty($request->user_pass) ? md5($request->user_pass) : $participant->getPassword();
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
            $customer = session()->user()->customer();
            $customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->where('participant_id',$request->id)->first();
            CustomerParticipant::delete($customerParticipant->id);
            return $this->index();
        }

        return ['status' => false];
    }

}
