<?php
namespace App\Controllers;
use App\Models\{Kuis,Sesi,Soal,Participant,ParticipantSession,SesiUser,ExamQuestion,ExamAnswer};
use App\Models\CustomerParticipant;
use PostMeta;

class KuisController
{
    function index()
    {
        return Kuis::where('post_author_id',session()->get('id'))->get();
    }

    function find($id)
    {
        $kuis = Kuis::where('id',$id)->where('post_author_id',session()->get('id'))->first();
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
                foreach($kuis->soal() as $soal)
                {
                    $jawaban = ExamAnswer::where('exam_question_id',$soal->id)->where('user_id',$partSesi->user_id)->first();
                    if($jawaban)
                        $skor += $jawaban->status;
                }
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
        $sesiUser->save([
            'post_id' => $request->sesi_id,
            'user_id' => $request->user_id,
        ]);

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
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $kuis = new Kuis;
                $kuis->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_status'    => 1,
                    'post_as'        => 'kuis',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

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
                $WaktuMulai->save([
                    'meta_value' => $request->waktu_mulai
                ]);

                $WaktuSelesai = PostMeta::where('post_id',$request->id)->where('meta_key','waktu_selesai')->first();
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

}
