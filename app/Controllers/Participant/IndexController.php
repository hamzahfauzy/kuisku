<?php
namespace App\Controllers\Participant;
use App\Models\{Participant,ParticipantSession,Soal,ExamQuestion,ExamAnswer,Jawaban};
use Post;

class IndexController 
{

    function index()
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $participant = Participant::find(session()->get('id'));
        $currentSession = 0;
        $nextSession = 0;
        foreach($participant->sesi() as $partSesi)
        {
            $partSesi->sesi();
            $partSesi->sesi->waktu_mulai = str_replace('T',' ',$partSesi->sesi->meta('waktu_mulai')).':00';
            $partSesi->sesi->waktu_selesai = str_replace('T',' ',$partSesi->sesi->meta('waktu_selesai')).':00';
            $waktu_mulai = strtotime($partSesi->sesi->waktu_mulai);
            $waktu_selesai = strtotime($partSesi->sesi->waktu_selesai);
            if($now > $waktu_mulai && $now < $waktu_selesai && !$currentSession)
            {
                $currentSession = $partSesi;
                session()->set('currentSession',$partSesi);
            }

            if($now < $waktu_mulai && !$nextSession)
            {
                $nextSession = $partSesi;
            }
        }
        return [
            'participant' => $participant,
            'currentSession' => $currentSession,
            'nextSession' => $nextSession,
        ];
    }

    function exam($no = 1)
    {
        $index = $no-1;
        $sesi = session()->get('currentSession');
        $partSesi = ParticipantSession::where('post_exam_id',$sesi->post_id)->where('user_id',session()->get('id'))->first();
        if($partSesi->status == 2)
            return redirect(route('participant'));

        if(empty($partSesi))
        {
            $kuis = $sesi->sesi->kuis();
            $soal = [];
            $all_soal = [];

            foreach($kuis->categories() as $category)
            {
                $_s = [];
                foreach($category->soal()  as $s)
                {
                    $_s[] = $s->id;
                    $all_soal[] = $s->id;
                }
                
                $_s = implode(',',$_s);
                $soal[$category->category_id] = [
                    'max' => $category->jumlah_soal,
                    'soal' => $_s
                ];
            }

            // print_r($soal);
            // return $soal;

            if(empty($all_soal))
            {
                showError('Maaf, Soal tidak di temukan untuk ujian ini');
                return;
            }
            
            $questions = [];
            foreach($soal as $key => $value)
            {
                $_soal = Soal::runRaw("SELECT id FROM posts WHERE id IN ($value[soal]) ORDER BY RAND() LIMIT 0,$value[max]");
                foreach($_soal as $val)
                    $questions[] = $val['id'];
            }

            $questions = implode(',',$questions);

            $partSession = new ParticipantSession;
            $id = $partSession->save([
                'post_exam_id' => $sesi->post_id,
                'user_id' => session()->get('id'),
                'questions_order' => $questions,
                'status' => 1,
            ]);

            $partSesi = ParticipantSession::find($id);
        }
        
        $questions = explode(',',$partSesi->questions_order);
        $soal = Soal::whereIn('id',$questions)->orderby("FIELD(id, $partSesi->questions_order)","")->get();

        $numOf = count($soal);

        $s = $soal[$index];
        // $examQuestion = ExamQuestion::where('post_exam_id',$partSesi->sesi()->post_parent_id)->where('post_question_id',$s->id)->first();
        $answer = ExamAnswer::where('exam_id',$partSesi->sesi()->post_parent_id)->where('question_id',$s->id)->where('user_id',session()->get('id'))->first();
        
        return ['sesi' => $sesi, 's' => $s, 'no' => $no, 'numOf' => $numOf, 'answered' => $answer];
    }

    function answer()
    {
        $request = request()->post();
        $sesi = session()->get('currentSession');
        $exam = $sesi->sesi->kuis();
        // $examQuestion = ExamQuestion::where('post_exam_id',$exam->id)->where('post_question_id',$request->question_id)->first();
        $jawaban = Jawaban::find($request->answer_id);

        $examAnswer = ExamAnswer::where('exam_id',$exam->id)->where('question_id',$request->question_id)->where('user_id',session()->get('id'))->first();
        // $examAnswer = ExamAnswer::where('exam_question_id',$examQuestion->id)->where('user_id',session()->get('id'))->first();
        if(!$examAnswer)
            $examAnswer = new ExamAnswer;

        $examAnswer->save([
            'exam_id' => $exam->id,
            'question_id' => $request->question_id,
            'post_answer_id'   => $request->answer_id,
            'user_id'          => session()->get('id'),
            'status'           => $jawaban->post_as
        ]);

        return ['status' => 'success'];
    }

    function finish()
    {
        $sesi = session()->get('currentSession');
        $partSesi = ParticipantSession::where('post_exam_id',$sesi->post_id)->where('user_id',session()->get('id'))->first();
        $partSesi->save([
            'status' => 2
        ]);

        return route('participant');
    }
}