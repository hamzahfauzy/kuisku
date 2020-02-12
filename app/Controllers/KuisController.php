<?php
namespace App\Controllers;
use App\Models\{Kuis,Sesi,Soal,Participant,SesiUser,ExamQuestion};
use PostMeta;

class KuisController
{
    function index()
    {
        return Kuis::get();
    }

    function find($id)
    {
        $kuis = Kuis::where('id',$id)->first();
        return $kuis;
    }

    function findSesi($id)
    {
        $kuis = Sesi::where('id',$id)->first();
        $kuis->meta->waktu_mulai = $kuis->meta('waktu_mulai');
        $kuis->meta->waktu_selesai = $kuis->meta('waktu_selesai');
        return $kuis;
    }

    function getSesi($id)
    {
        $kuis = $this->find($id);
        foreach($kuis->sesi() as $sesi)
        {
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
        
        $all_soal = Soal::whereNotIn('id',$soal)->get();
        foreach($all_soal as $question)
            $question->categories();
        return ['kuis'=>$kuis,'allSoal'=>$all_soal];
    }

    function view($id)
    {
        $kuis = Kuis::where('id',$id)->first();
        return ['kuis'=>$kuis];
    }

    function viewSesi($id)
    {
        $sesi = Sesi::where('id',$id)->first();
        $sesi->waktu_mulai = str_replace('T',' ',$sesi->meta('waktu_mulai'));
        $sesi->waktu_selesai = str_replace('T',' ',$sesi->meta('waktu_selesai'));
        $sesi->peserta();
        $all_sesi = Kuis::where('id',$sesi->post_parent_id)->first();
        $peserta = [];
        foreach($all_sesi->sesi() as $_sesi){
            foreach($_sesi->peserta() as $_p)
                $peserta[] = $_p->user()->id;
        }
        $exclude = Participant::whereNotIn('id',$peserta)->get();
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
                $kuis = Kuis::find($request->id);
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
                $kuis = Sesi::find($request->id);
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
            $kuis = Sesi::find($request->id);
            $post_parent_id = $kuis->post_parent_id;
            Sesi::delete($request->id);
            return $this->getSesi($post_parent_id);
        }

        return ['status' => false];
    }

}
