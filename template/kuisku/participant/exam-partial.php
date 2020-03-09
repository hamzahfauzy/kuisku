
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
                    <div class="container-fluid">
                        <?= $s->post_content ?>
                        <br>
                        <hr>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="exam-options row">
                        <?php foreach($jwb as $answer): ?>
                        <div class="inputGroup col-sm-12 col-md-6">
                            <input onchange="sendAnswer(<?=$answer->id?>,<?=$s->id?>)" id="radio<?=$answer->id?>" name="answers[<?=$s->id?>]" type="radio" value="<?= $answer->id ?>" <?= isset($answered->post_answer_id) && $answer->id == $answered->post_answer_id ? 'checked=""' : '' ?>/>
                            <label for="radio<?=$answer->id?>"><?= $answer->post_content ?></label>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <br>
                </div>
                <div class="finish-section container">
                    <?php if($no != 1): ?>
                    <a href="<?= route('participant/exam-partial/'.($no-1)) ?>" data-page="<?=$no-1?>" class="btn btn-success" onclick="prevQuestion(this)"><i class="fa fa-arrow-left fa-fw"></i> Sebelumnya</a>
                    <?php endif ?>

                    <?php if($no == $numOf): ?>
                    <a href="javascript:void(0)" onclick="finishExam()" class="btn btn-primary"><i class="fa fa-check"></i> Selesai</a>
                    <?php endif ?>
                    
                    <?php if($no != $numOf): ?>
                    <a href="<?= route('participant/exam-partial/'.($no+1)) ?>" data-page="<?=$no+1?>" class="btn btn-success" onclick="nextQuestion(this)"><i class="fa fa-arrow-right fa-fw"></i> Selanjutnya</a>
                    <?php endif ?>
                </div>
            </form>
            </div>
        </div>
    </div>
