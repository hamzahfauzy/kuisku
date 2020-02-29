<?php 
$this->title .= " | Kuis"; 
$this->visited = "kuis";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];

$waktu_selesai = str_replace(' ','T',$sesi->sesi->waktu_selesai);
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="exam-panel">
    <div class="exam-container">
        <span>Sisa Waktu</span>
        <h4 style="margin:0" id="countdown"></h4>
    </div>
</div>
<div class="container-fluid exam-html">
    
</div>

<script>
var deadline = new Date("<?=$waktu_selesai?>").getTime(); 
var x = setInterval(function() { 
var now = new Date().getTime(); 
var t = deadline - now; 
var days = Math.floor(t / (1000 * 60 * 60 * 24)); 
var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60)); 
var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60)); 
var seconds = Math.floor((t % (1000 * 60)) / 1000); 
days = days < 10 ? "0"+days : days;
hours = hours < 10 ? "0"+hours : hours;
minutes = minutes < 10 ? "0"+minutes : minutes;
seconds = seconds < 10 ? "0"+seconds : seconds;
document.getElementById("countdown").innerHTML = hours + ":" + minutes + ":" + seconds; 
    if (t < 0) { 
        clearInterval(x); 
        location=location
    } 
}, 1000); 

function loadExam(url = false)
{
    if(!url)
        url = '<?= route('participant/exam-partial') ?>'
    fetch(url)
    .then(res => res.text())
    .then(res => {
        document.querySelector('.exam-html').innerHTML = res
    })

}

async function sendAnswer(jawaban, soal)
{
    let request = await fetch('<?= route('participant/exam/answer') ?>',{
        method:'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify({answer_id:jawaban, question_id:soal}),
    })

    let response = await request.json()
    console.log(response)
}

function finishExam()
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan menyelesaikan ujian ini ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            location='<?= route('participant/exam/finish') ?>'
        }
    })
}

function nextQuestion(el)
{
    event.preventDefault()
    loadExam(el.href)
}

function prevQuestion(el)
{
    event.preventDefault()
    loadExam(el.href)
}

loadExam()
</script>