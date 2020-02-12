<?php
namespace App\Controllers\Participant;
use App\Models\{Participant,ParticipantSession,Soal};
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
            $waktu_mulai = strtotime($partSesi->sesi->waktu_mulai,date('Y-m-d H:i:s'));
            $waktu_selesai = strtotime($partSesi->sesi->waktu_selesai,date('Y-m-d H:i:s'));
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

    function exam()
    {
        $no = isset($_GET['question']) ? $_GET['question'] : 1;
        $index = $no-1;
        $sesi = session()->get('currentSession');
        $partSesi = ParticipantSession::where('post_exam_id',$sesi->post_id)->where('user_id',session()->get('id'))->first();
        if(empty($partSesi))
        {
            $sesi->sesi->kuis()->soal();
            $questions = [];
            foreach($sesi->sesi->kuis->soal as $soal)
            $questions[] = $soal->post_question_id;
            $questions = implode(',',$questions);
            
            $soal = Soal::runRaw("SELECT id FROM posts WHERE id IN ($questions) ORDER BY RAND()");
            $questions = [];
            foreach($soal as $val)
                $questions[] = $val['id'];

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
        
        return ['sesi' => $sesi, 's' => $soal[$index], 'no' => $no, 'numOf' => $numOf];
    }
}