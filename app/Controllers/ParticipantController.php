<?php
namespace App\Controllers;
use User;
use UserMeta;
use ZSms;
use TemplatePartial;
use SpreadsheetReader;
use App\Models\{CustomerParticipant, Participant};

class ParticipantController
{

    function test()
    {
        $mail = new ZMail;
        $request = json_decode(json_encode([
            'user_email' => 'ripsilanti@gmail.com',
            'user_pass'  => 'password'
        ]));

        ob_start();
        new TemplatePartial([
            'data' => $request
        ],"mail-template/add-participant");
        $message = ob_get_clean();

        $send = $mail->send($request->user_email,"Pendaftaran Peserta Ujian",$message);
        if($send != 1)
            return ['status' => false, 'message' => $send];
        return ['status' => 'success'];
    }

    function import()
    {
        $file      = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_name_array = explode(".", $file_name);
        $allowedFileType = ['application/vnd.ms-excel','text/xls','text/csv','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $extension = end($file_name_array);
        if(in_array($_FILES["file"]["type"],$allowedFileType)){
            $new_file_name  = time() . "" . rand() . '.' . $extension;
            $targetPath = 'uploads/'.$new_file_name;
            move_uploaded_file($file, $targetPath);
            $Reader    = new SpreadsheetReader($targetPath);

            $Sheets = $Reader -> Sheets();

            foreach ($Sheets as $Index => $Name)
            {
                echo 'Sheet #'.$Index.': '.$Name;

                $Reader -> ChangeSheet($Index);
                foreach($Reader as $row)
                {
                    print_r($row);
                }
                    
            }
                
            return [];
        }
    }

    function index()
    {
        $customer = session()->user()->customer();
        $count_of_participant = CustomerParticipant::where('customer_id',$customer->id)->count();
        $participants = []; //$customer->participants();
        $limit = 10;
        $num_of_page = ceil($count_of_participant/$limit);
        return [
            'count_of_participant' => $count_of_participant,
            'num_of_page' => $num_of_page
        ];
    }

    function get()
    {
        $customer = session()->user()->customer();
        $participants = [];
        // $participants = $customer->participants();
        // foreach($participants as $participant)
        //     $participant->no_hp = $participant->meta('no_hp');
        // return $participants;
        $limit = 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $page = $page-1;
        $page = $limit * $page;
        $customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->limit($page.', '.$limit)->get();
        foreach($customerParticipant as $custPart)
        {
            $participant = $custPart->participant();
            $participant->no_hp = $participant->meta('no_hp');
            $participants[] = $participant;
        }
        return $participants;
    }

    function find($id)
    {
        $customer = session()->user()->customer();
        $customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->where('participant_id',$id)->first();
        $participant = $customerParticipant->participant();
        $participant->no_hp = $participant->meta('no_hp');
        return $participant;
    }

    function insert()
    {
        $request = request()->post();
        $customer = session()->user()->customer();
        $subscription_active = $customer->subscription_active();
        if($subscription_active)
        {
            $product = $subscription_active->product();
            $participants = CustomerParticipant::where('customer_id',$customer->id)->get();
            if(count($participants)+1 > $product->limit_peserta)
                return ['status'=>false,'message'=>'Jumlah peserta sudah melewati batas'];
            if($request)
            {
                $customer = session()->user()->customer();

                $validate = [
                    'user_name'   => ['required'],
                    'user_email'  => ['required'],
                    'user_pass'   => ['required'],
                    'no_hp'       => ['required'],
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

                        $user_meta = new UserMeta;
                        $user_meta->save([
                            'user_id'    => $participant_id,
                            'meta_key'   => 'no_hp',
                            'meta_value' => $request->no_hp
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
                    $_customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->where('participant_id',$participant_id)->first();
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
                'no_hp'       => ['required'],
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

                $user_meta = UserMeta::where('user_id',$request->id)->where('meta_key','no_hp')->first();
                if($user_meta)
                    $user_meta->save([
                        'meta_value' => $request->no_hp
                    ]);
                else
                {
                    $user_meta = new UserMeta;
                    $user_meta->save([
                        'user_id'    => $request->id,
                        'meta_key'   => 'no_hp',
                        'meta_value' => $request->no_hp
                    ]);
                }

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
            return $this->get();
        }

        return ['status' => false];
    }

    function notifikasiPeserta()
    {
        $request = request()->post();
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars),0,10);
        $participant = Participant::find($request->user_id);
        $participant->save([
            'user_pass'   => md5($password), 
        ]);

        $customer = session()->user()->customer();
        $nama = "PT. Kawasan Industri Nusantara";
        $email = $participant->user_login;
        $email = str_replace('@','[at]',$email);
        $message = "Info Test ".$nama.", Link: s.id/eCQGX, Email: ".$email.", Sandi: ".$password.", masuk untuk melihat jadwal ujian";

        $sms = new ZSms;
        $response = $sms->send($participant->meta('no_hp'),$message);

        return ['status' => 1,'message' => $response];
    }

}
