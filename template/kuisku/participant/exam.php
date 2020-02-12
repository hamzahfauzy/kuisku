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
            <?php foreach($soal as $s): ?>
                <div class="exam-question">
                    <div class="container">
                        <h3><?= $s->post_title ?></h3>
                        <?= $s->post_content ?>
                        <br>
                        <hr>
                    </div>
                </div>
                <div class="container">
                    <div class="exam-options row">
                        <?php foreach($s->answers() as $answer): ?>
                        <div class="inputGroup col-sm-12 col-md-6">
                            <input id="radio<?=$answer->id?>" name="answers<?=$s->id?>" type="radio" value="<?= $answer->id ?>"/>
                            <label for="radio<?=$answer->id?>"><?= $answer->post_content ?></label>
                        </div>
                        <?php endforeach ?>
                    </div>    
                    <br><br><br>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</div>