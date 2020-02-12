<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->application_name ?> | Peserta</title>
    <link rel="stylesheet" type="text/css" href="<?= asset('css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= asset('css/font-awesome.min.css') ?>">
    <link href="<?= asset('css/app.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?= asset('css/participant.css') ?>" type="text/css" rel="stylesheet"/>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/popper.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
</head>
<body>
    <?php require __DIR__ .'/../layouts/header.php' ?>
    <div class="content">
        <div class="participant-info">
            <center>
                <div class="participant-picture">
                    <img src="<?= asset('assets/user.png') ?>" width="100px" height="100px" style="display:block">
                </div>
                <h3>Selamat Datang, <?= session()->user()->user_name; ?></h3>
			    <span class="email-info"><?= session()->user()->user_email ?> <br></span>
                <span class="ip-info">IP: <?= getUserIpAddr() ?></span>
                <br><br>
                <?php if($currentSession || ($currentSession && $currentSession->partSesi()->status == 1)): ?>
                    <a href="<?= route('participant/exam') ?>" class="btn btn-success">Ikuti Ujian</a> <br><br>
                <?php elseif($currentSession && $currentSession->partSesi()->status == 2): ?>
                    <div class="alert alert-success">Anda sudah menyelesaikan Ujian</div>
                <?php elseif($nextSession): ?>
                    <a href="#" class="btn btn-success">Ujian akan di laksanakan pada <?= $nextSession->sesi->waktu_mulai ?></a> <br><br>
                <?php else: ?>
                    <div class="alert alert-danger">Maaf!. Tidak ada jadwal ujian untuk anda. </div>
                <?php endif ?>
                <a href="<?= route('logout') ?>"><i class="fa fa-sign-out"></i> Log Out</a>
            </center>
        </div>
    </div>
</body>
</html>