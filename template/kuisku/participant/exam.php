<?php 
$this->title .= " | Kuis"; 
$this->visited = "kuis";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];

$waktu_selesai = str_replace(' ','T',$waktu_selesai);
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="exam-panel">    
    <div class="exam-container">
        <span>Sisa Waktu</span>
        <h4 style="margin:0" id="countdown"></h4>
    </div>
</div>
<div class="question-navigation-panel" onclick="showQuestionNavigation()">
<a href="javascript:void(0)" class="caret-toggle"><i class="fa fa-caret-left"></i></a>
</div>
<div class="question-navigation">
    <div class="container-fluid">
        <div class="row question-nav-item">
            
        </div>
    </div>
</div>
<div class="container-fluid exam-html">
    
</div>

<script>
var deadline = new Date("<?=$waktu_selesai?>").getTime(); 
var x = setInterval(function() { 
    var now = new Date().getTime(); 
    var t = deadline - now; 
    if (t <= 0) { 
        clearInterval(x); 
        location='<?= route('participant/exam/finish') ?>'
    }
    var days = Math.floor(t / (1000 * 60 * 60 * 24)); 
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60)); 
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60)); 
    var seconds = Math.floor((t % (1000 * 60)) / 1000); 
    days = days < 10 ? "0"+days : days;
    hours = hours < 10 ? "0"+hours : hours;
    minutes = minutes < 10 ? "0"+minutes : minutes;
    seconds = seconds < 10 ? "0"+seconds : seconds;
    document.getElementById("countdown").innerHTML = hours + ":" + minutes + ":" + seconds;  
}, 1000); 

var currentQuestion = 1

function showQuestionNavigation()
{
    var navToggle = document.querySelector('.question-navigation-panel')
    navToggle.classList.toggle("question-navigation-panel-show");

    if(navToggle.classList.contains("question-navigation-panel-show"))
    {
        document.querySelector('.caret-toggle').innerHTML = `<i class="fa fa-caret-right"></i>`
    }
    else
    {
        document.querySelector('.caret-toggle').innerHTML = `<i class="fa fa-caret-left"></i>`
    }

    var navPanel = document.querySelector('.question-navigation')
    navPanel.classList.toggle("question-navigation-show");
}

function loadExam(url = false, page = 1)
{
    if(!url)
        url = '<?= route('participant/exam-partial') ?>'
    fetch(url)
    .then(res => res.text())
    .then(res => {
        document.querySelector('.exam-html').innerHTML = res
        loadNavigation(page)
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
    loadNavigation(currentQuestion)
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
    currentQuestion = el.dataset.page
    loadExam(el.href, el.dataset.page)
}

function prevQuestion(el)
{
    event.preventDefault()
    currentQuestion = el.dataset.page
    loadExam(el.href, el.dataset.page)
}

function loadNavigation(page = 1)
{
    fetch("<?= base_url() ?>/participant/load-navigation?page="+page)
    .then(res => res.json())
    .then(res => {
        console.log(res)
        document.querySelector('.question-nav-item').innerHTML = ''
        for(i=0;i<res.numOf;i++){
            var number = i+1
            var answered = res.answered.find(e => e.question_id == res.s[i].id)
            answered = answered != undefined ? 'btn-success' : 'btn-secondary'
            answered = number == res.no ? 'btn-primary' : answered
            document.querySelector('.question-nav-item').innerHTML += `
            <div class="col-sm-4" style="margin-bottom:10px">
                <a href="<?= base_url() ?>/participant/exam-partial/${number}" onclick="nextQuestion(this)" data-page="${number}" class="btn btn-block ${answered}">${number}</a>
            </div>
            `
        }
    })
}

loadExam()
</script>