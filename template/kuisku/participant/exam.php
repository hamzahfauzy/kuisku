<?php 
$this->title .= " | Kuis"; 
$this->visited = "kuis";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
    asset('js/ckeditor.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Ujian</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper" style="font-size:16px;line-height:2;">
            <form method="post">
                <input type="hidden" name="exam" value="1">
                <div class="exam-question">
                    <div class="container">
                        <h3><?= $s->post_title ?></h3>
                        <a href="#"><i class="fa fa-tag"></i>
                        <?php 
                        foreach($s->categories() as $category):
                            echo $category->category->category_name;
                            if($category != end($s->categories))
                                echo ",";
                        endforeach; 
                        ?>
                        </a>
                        <hr>
                        <?= $s->post_content ?>
                        <br>
                        <hr>
                    </div>
                </div>
                <div class="container">
                    <div class="exam-options row">
                        <?php foreach($s->answers() as $answer): ?>
                        <div class="inputGroup col-sm-12 col-md-6">
                            <input onchange="sendAnswer(<?=$answer->id?>,<?=$s->id?>)" id="radio<?=$answer->id?>" name="answers[<?=$s->id?>]" type="radio" value="<?= $answer->id ?>" <?= $answer->id == $answered->post_answer_id ? 'checked=""' : '' ?>/>
                            <label for="radio<?=$answer->id?>"><?= $answer->post_content ?></label>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <br>
                </div>
                <div class="finish-section container">
                    <?php if($no != 1): ?>
                    <a href="<?= route('participant/exam') ?>?question=<?=$no-1?>" class="btn btn-success"><i class="fa fa-arrow-left fa-fw"></i> Sebelumnya</a>
                    <?php endif ?>

                    <?php if($no == $numOf): ?>
                    <a href="javascript:void(0)" onclick="finishExam()" class="btn btn-primary"><i class="fa fa-check"></i> Selesai</a>
                    <?php endif ?>
                    
                    <?php if($no != $numOf): ?>
                    <a href="<?= route('participant/exam') ?>?question=<?=$no+1?>" class="btn btn-success"><i class="fa fa-arrow-right fa-fw"></i> Selanjutnya</a>
                    <?php endif ?>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>