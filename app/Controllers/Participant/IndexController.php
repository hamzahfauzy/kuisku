<?php
namespace App\Controllers\Participant;
use App\Models\Participant;
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
        $sesi = session()->get('currentSession');
        // $sesi->
        return ['sesi' => session()->get('currentSession')];
    }
}