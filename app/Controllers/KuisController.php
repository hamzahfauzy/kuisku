<?php
namespace App\Controllers;
use App\Models\{Kuis,Sesi,Soal,Participant,ParticipantSession,SesiUser,ExamQuestion,ExamAnswer,CategoryKuis};
use App\Models\CustomerParticipant;
use PostMeta;
use UserMeta;
use ZMail;
use TemplatePartial;
use SpreadsheetReader;
use User;

class KuisController
{
    function index()
    {
        $kuis = Kuis::where('post_author_id',session()->get('id'))->get();
        foreach($kuis as $val)
            $val->max_participant = $val->meta('max_participant');
        return $kuis;
    }

    function find($id)
    {
        $kuis = Kuis::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        $kuis->max_participant = $kuis->meta('max_participant');
        return $kuis;
    }

    function findSesi($id)
    {
        $kuis = Sesi::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        $kuis->meta->waktu_mulai = $kuis->meta('waktu_mulai');
        $kuis->meta->waktu_selesai = $kuis->meta('waktu_selesai');
        return $kuis;
    }

    function getSesi($id)
    {
        $kuis = $this->find($id);
        foreach($kuis->sesi() as $sesi)
        {
            $sesi->now = date('Y-m-d H:i:s');
            $sesi->waktu_mulai = str_replace('T',' ',$sesi->meta('waktu_mulai'));
            $sesi->waktu_selesai = str_replace('T',' ',$sesi->meta('waktu_selesai'));
        }
        return $kuis->sesi;
    }

    function getSoal($id)
    {
        $kuis = $this->find($id);
        $soal = [];
        foreach($kuis->soal() as $val)
        {
            $val->soal->categories();
            $soal[] = $val->post_question_id;
        }
        
        $all_soal = Soal::whereNotIn('id',$soal)->where('post_author_id',session()->get('id'))->get();
        foreach($all_soal as $question)
            $question->categories();
        return ['kuis'=>$kuis,'allSoal'=>$all_soal];
    }

    function view($id)
    {
        $kuis = Kuis::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        return ['kuis'=>$kuis];
    }

    function participant($id)
    {
        $kuis = Kuis::where('post_author_id',session()->get('id'))->where('id',$id)->first();
        $participant = [];
        foreach($kuis->sesi() as $sesi)
        {
            foreach($sesi->partSession() as $partSesi)
            {
                $skor = 0;
                $jawaban = ExamAnswer::where('exam_id',$kuis->id)->where('user_id',$partSesi->user_id)->get();
                foreach($jawaban as $jwb)
                    $skor += $jwb->status;
                $partSesi->skor = $skor;
                $partSesi->sesi();
                $partSesi->sesi->waktu_mulai = str_replace('T',' ',$partSesi->sesi->meta('waktu_mulai')).":00";
                $partSesi->sesi->waktu_selesai = str_replace('T',' ',$partSesi->sesi->meta('waktu_selesai')).":00";
                $partSesi->sesi->now = date('Y-m-d H:i:s');
                $participant[] = $partSesi;
            }
        }

        return $participant;
    }

    function scoreboard($id)
    {
        $kuis = Kuis::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        return ['kuis'=>$kuis];
    }

    function viewSesi($id)
    {
        $customer = session()->user()->customer();
        $sesi = Sesi::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        if(empty($sesi)) return false;
        $sesi->waktu_mulai = str_replace('T',' ',$sesi->meta('waktu_mulai'));
        $sesi->waktu_selesai = str_replace('T',' ',$sesi->meta('waktu_selesai'));
        $sesi->peserta();
        $sesi->now = date('Y-m-d H:i:s');
        $all_sesi = Kuis::where('id',$sesi->post_parent_id)->where('post_author_id',session()->get('id'))->first();
        $peserta = [];
        $all_participants = [];
        foreach($customer->participants() as $participant)
        {
            $all_participants[$participant->id] = $participant->id;
        }
        // print_r($all_participants);
        foreach($all_sesi->sesi() as $_sesi){
            foreach($_sesi->peserta() as $_p)
            {
                // $peserta[] = $_p->user()->id;
                unset($all_participants[$_p->user()->id]);
            }
        }
        // print_r($peserta);
        $exclude = Participant::whereIn('id',$all_participants)->get();
        return ['sesi' => $sesi, 'exclude' => $exclude];
    }

    function sesiJadiPeserta()
    {
        $request = request()->post();

        $sesiUser = new SesiUser;
        $sesiUserId = $sesiUser->save([
            'post_id' => $request->sesi_id,
            'user_id' => $request->user_id,
        ]);

        // $sesiUser = SesiUser::where('id',$sesiUserId)->first();

        // $mail = new ZMail;

        // ob_start();
        // new TemplatePartial([
        //     'data' => $sesiUser
        // ],"mail-template/notif-participant");
        // $message = ob_get_clean();

        // $send = $mail->send($sesiUser->user()->user_email,"Informasi Jadwal Ujian",$message);
        // if($send != 1)
        //     return ['status' => false, 'message' => $send];

        return ['status' => 1];
    }

    function sesiBatalPeserta()
    {
        $request = request()->post();
        $sesiUser = SesiUser::where('post_id',$request->sesi_id)->where('user_id',$request->user_id)->first();

        SesiUser::delete($sesiUser->id);

        return ['status' => 1];
    }

    function tambahSoal()
    {
        $request = request()->post();
        $exam = new ExamQuestion;
        $exam->save([
            'post_exam_id' => $request->kuis_id,
            'post_question_id' => $request->soal_id,
        ]);

        return ['status' => 1];
    }

    function hapusSoal()
    {
        $request = request()->post();
        $exam = ExamQuestion::where('post_exam_id',$request->kuis_id)->where('post_question_id',$request->soal_id)->first();

        ExamQuestion::delete($exam->id);

        return ['status' => 1];
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_title'   => ['required'],
                'post_content' => ['required'],
                'max_participant' => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $kuis = new Kuis;
                $kuis_id = $kuis->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_status'    => 1,
                    'post_as'        => 'kuis',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                $kuis_meta = new PostMeta;
                $kuis_meta->save([
                    'post_id' => $kuis_id,
                    'meta_key' => 'max_participant',
                    'meta_value' => $request->max_participant
                ]);

                return $this->index();
            }
            
        }

        return ['status' => false];
    }

    function insertSesi($id)
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_title'   => ['required'],
                'post_content' => ['required'],
                'waktu_mulai'  => ['required'],
                'waktu_selesai'  => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $kuis = new Sesi;
                $sesi_id = $kuis->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_status'    => 0,
                    'post_parent_id' => $id,
                    'post_as'        => 'sesi',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                $WaktuMulai = new PostMeta;
                $WaktuMulai->save([
                    'post_id' => $sesi_id,
                    'meta_key' => 'waktu_mulai',
                    'meta_value' => $request->waktu_mulai
                ]);

                $WaktuSelesai = new PostMeta;
                $WaktuSelesai->save([
                    'post_id' => $sesi_id,
                    'meta_key' => 'waktu_selesai',
                    'meta_value' => $request->waktu_selesai
                ]);

                return $this->getSesi($id);
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
                'post_title' => ['required'],
                'post_content' => ['required'],
                'max_participant' => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $kuis = Kuis::where('post_author_id',session()->get('id'))->where('id',$request->id)->first();
                $kuis->save([
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                $kuis_meta = PostMeta::where('post_id',$request->id)->where('meta_key','max_participant')->first();
                if($kuis_meta)
                    $kuis_meta->save([
                        'meta_value' => $request->max_participant
                    ]);
                else
                {
                    $kuis_meta = new PostMeta;
                    $kuis_meta->save([
                        'post_id' => $request->id,
                        'meta_key' => 'max_participant',
                        'meta_value' => $request->max_participant
                    ]);
                }

                return $this->index();
            }
            
        }

        return ['status' => false];
    }

    function updateSesi()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_title' => ['required'],
                'post_content' => ['required'],
                'waktu_mulai'  => ['required'],
                'waktu_selesai'  => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $kuis = Sesi::where('post_author_id',session()->get('id'))->where('id',$request->id)->first();
                $post_parent_id = $kuis->post_parent_id;
                $kuis->save([
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                $WaktuMulai = PostMeta::where('post_id',$request->id)->where('meta_key','waktu_mulai')->first();
                if(empty($WaktuMulai))
                {
                    $kuis_meta = new PostMeta;
                    $kuis_meta->save([
                        'post_id' => $request->id,
                        'meta_key' => 'waktu_mulai',
                        'meta_value' => $request->waktu_mulai
                    ]);
                }
                else
                    $WaktuMulai->save([
                        'meta_value' => $request->waktu_mulai
                    ]);

                $WaktuSelesai = PostMeta::where('post_id',$request->id)->where('meta_key','waktu_selesai')->first();
                if(empty($WaktuSelesai))
                {
                    $kuis_meta = new PostMeta;
                    $kuis_meta->save([
                        'post_id' => $request->id,
                        'meta_key' => 'waktu_selesai',
                        'meta_value' => $request->waktu_selesai
                    ]);
                }
                else
                    $WaktuSelesai->save([
                        'meta_value' => $request->waktu_selesai
                    ]);

                return $this->getSesi($post_parent_id);
            }
            
        }

        return ['status' => false];
    }

    function delete()
    {
        $request = request()->post();
        if($request)
        {
            Kuis::delete($request->id);
            return $this->index();
        }

        return ['status' => false];
    }

    function deleteSesi()
    {
        $request = request()->post();
        if($request)
        {
            $kuis = Sesi::where('post_author_id',session()->get('id'))->where('id',$request->id)->first();
            $post_parent_id = $kuis->post_parent_id;
            Sesi::delete($request->id);
            return $this->getSesi($post_parent_id);
        }

        return ['status' => false];
    }

    function saveCategory()
    {
        $request = request()->post();
        foreach($request->category_setting as $key => $value)
        {
            $categoryKuis = CategoryKuis::where('kuis_id',$request->kuis_id)->where('category_id',$key)->first();
            if($categoryKuis)
                $categoryKuis->save([
                    'jumlah_soal' => $value
                ]);
            else
            {

                $categoryKuis = new CategoryKuis;
                $categoryKuis->save([
                    'kuis_id' => $request->kuis_id,
                    'category_id' => $key,
                    'jumlah_soal' => $value
                ]);
            }
        }

        return ['status' => true];
    }

    function getCategory($id)
    {
        $categoryKuis = CategoryKuis::where('kuis_id',$id)->get();
        return $categoryKuis;
    }

    function importParticipant()
    {
        $file      = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_name_array = explode(".", $file_name);
        $allowedFileType = ['application/vnd.ms-excel','text/xls','text/csv','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $extension = end($file_name_array);
        if(in_array($_FILES["file"]["type"],$allowedFileType)){
            $request = request()->post();
            $new_file_name  = time() . "" . rand() . '.' . $extension;
            $targetPath = 'uploads/'.$new_file_name;
            move_uploaded_file($file, $targetPath);
            $Reader    = new SpreadsheetReader($targetPath);

            $Sheets = $Reader->Sheets();
            $ret = [];
            $customer = session()->user()->customer();
            $Reader->ChangeSheet(0);
            foreach($Reader as $key => $row)
            {
                if($key == 0) continue;
                $email = $row[2];
                $no_hp = $row[3];
                $no_hp = str_replace("'",'',$no_hp);
                $no_hp = str_replace(" ","",$no_hp);
                $user_checker = User::where('user_email',$email)->where('user_level','!=','participant')->first();
                    
                if($user_checker)
                    continue;

                $_participant = Participant::where('user_email',$email)->first();
                if(!$_participant)
                {
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $password = substr(str_shuffle($chars),0,$length);
                    $participant = new Participant;
                    $participant_id = $participant->save([
                        'user_name'   => $row[1],
                        'user_email'  => $email,
                        'user_login'  => $email,
                        'user_pass'   => md5($password), 
                        'user_status' => 1,
                    ]);

                    $user_meta = new UserMeta;
                    $user_meta->save([
                        'user_id'    => $participant_id,
                        'meta_key'   => 'no_hp',
                        'meta_value' => $no_hp
                    ]);

                    $customerParticipant = new CustomerParticipant;
                    $customerParticipant->save([
                        'customer_id' => $customer->id,
                        'participant_id' => $participant_id
                    ]);

                    $ret[$participant_id] = [
                        'participant_id' => $participant_id,
                        'nama'  => $row[1],
                        'email' => $email,
                        'no_hp' => $no_hp,
                    ];
                }
                else
                {
                    $participant_id = $_participant->id;
                    $_customerParticipant = CustomerParticipant::where('customer_id',$customer->id)->where('participant_id',$participant_id)->first();
                    if(!$_customerParticipant)
                    {
                        $customerParticipant = new CustomerParticipant;
                        $customerParticipant->save([
                            'customer_id' => $customer->id,
                            'participant_id' => $participant_id
                        ]);
                    }
                    $ret[$participant_id] = [
                        'participant_id' => $participant_id,
                        'nama'  => $row[1],
                        'email' => $email,
                        'no_hp' => $no_hp,
                    ];
                }
            }

            $all_sesi = Kuis::where('id',$request->id)->where('post_author_id',session()->get('id'))->first();
            foreach($all_sesi->sesi() as $_sesi){
                foreach($_sesi->peserta() as $_p)
                {
                    unset($ret[$_p->user()->id]);
                }
            }

            if(empty($ret)) return ['status' => true];

            $kuis = Kuis::find($request->id);
            $max_participant = $kuis->meta('max_participant');
            $pembagian_jumlah_sesi = ceil(count($ret) / $max_participant);
            $sisa_pembagian = count($ret) % $max_participant;
            if($sisa_pembagian) $pembagian_jumlah_sesi += 1;
            $partitions = array_chunk($ret,$pembagian_jumlah_sesi,true);
            foreach($partitions as $key => $partition)
            {
                $no = $key+1;
                $title = "Sesi ".$no;
                $kuis = new Sesi;
                $sesi_id = $kuis->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $title,
                    'post_content'   => $title,
                    'post_excerpt'   => $title,
                    'post_status'    => 0,
                    'post_parent_id' => $request->id,
                    'post_as'        => 'sesi',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                foreach($partition as $participant)
                {
                    $sesiUser = new SesiUser;
                    $sesiUserId = $sesiUser->save([
                        'post_id' => $sesi_id,
                        'user_id' => $participant['participant_id'],
                    ]);
                }
            }
            return ['status'=>true];
        }
    }

}
