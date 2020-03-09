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
    <script src="<?= asset('js/sweetalert2@9.js') ?>"></script>
    <script src="<?= asset('js/sweetalert2.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
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
                <span class="ip-info">IP: <?= getUserIpAddr() ?></span><br>
                <br><br>
                <?php if($currentSession): ?>
                    <?php if(!$currentSession->partSesi() || $currentSession->partSesi->status == 1 || (isset($currentSession->status_durasi) && $currentSession->status_durasi == 1)): ?>
                    <?php $kuis = $currentSession->sesi->kuis(); ?>
                    <?php if($currentSession->partSesi && $currentSession->partSesi->status == 2): ?>
                    <div class="alert alert-success">Anda sudah menyelesaikan Ujian</div>
                    <?php else: ?>
                    <a href="<?= route('participant/exam') ?>" class="btn btn-success">Ikuti Ujian</a> <br><br>
                    <?php endif; ?>
                    <table class="table table-bordered">
                        <tr>
                            <td>Nama Ujian</td>
                            <td><?= $kuis->post_title ?></td>
                        </tr>
                        <tr>
                            <td>Jadwal Mulai</td>
                            <td><?= (new \DateTime($currentSession->sesi->waktu_mulai))->format('d-m-Y H:i') ?></td>
                        </tr>
                        <tr>
                            <td>Jadwal Selesai</td>
                            <td><?= (new \DateTime($currentSession->sesi->waktu_selesai))->format('d-m-Y H:i') ?></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td><?= $kuis->post_content ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Soal</td>
                            <td>
                            <?php 
                            $jumlah_soal = 0;
                            foreach($kuis->categories() as $category)
                                $jumlah_soal += $category->jumlah_soal;
                            ?>
                            <?= $jumlah_soal ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Waktu Mengerjakan</td>
                            <td>
                            <?= $kuis->meta('durasi')." menit" ?>
                            </td>
                        </tr>
                    </table>
                    <?php else: ?>
                    <div class="alert alert-success">Anda sudah menyelesaikan Ujian</div>
                    <?php endif ?>
                <?php elseif($nextSession): ?>
                    <?php $kuis = $nextSession->sesi->kuis(); ?>
                    <a href="#" class="btn btn-success">Ujian akan di laksanakan pada <?= (new \DateTime($nextSession->sesi->waktu_mulai))->format('d-m-Y H:i') ?></a> <br><br>
                    <table class="table table-bordered">
                        <tr>
                            <td>Nama Ujian</td>
                            <td><?= $kuis->post_title ?></td>
                        </tr>
                        <tr>
                            <td>Jadwal Mulai</td>
                            <td><?= (new \DateTime($nextSession->sesi->waktu_mulai))->format('d-m-Y H:i') ?></td>
                        </tr>
                        <tr>
                            <td>Jadwal Selesai</td>
                            <td><?= (new \DateTime($nextSession->sesi->waktu_selesai))->format('d-m-Y H:i') ?></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td><?= $kuis->post_content ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Soal</td>
                            <td>
                            <?php 
                            $jumlah_soal = 0;
                            foreach($kuis->categories() as $category)
                                $jumlah_soal += $category->jumlah_soal;
                            ?>
                            <?= $jumlah_soal ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Waktu Mengerjakan</td>
                            <td>
                            <?= $kuis->meta('durasi')." menit" ?>
                            </td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="alert alert-danger">Maaf!. Tidak ada jadwal ujian untuk anda. </div>
                <?php endif ?>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Ubah Password</a>
                |
                <a href="<?= route('logout') ?>"><i class="fa fa-sign-out"></i> Log Out</a>
            </center>
        </div>
    </div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" onsubmit="changePassword(this)" id="updatePassword" action="<?= route('change-password') ?>">
      <div class="modal-body">
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text" onclick="showPassword('#updatePassword')">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="input-group-text" onclick="generatePassword('#updatePassword')">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
function generatePassword(el)
{
    el = $(el).find('#password')
    var randomstring = Math.random().toString(36).slice(-10);
    el.val(randomstring)
}

function showPassword(el)
{
    el = $(el).find('#password')
    var changeType = el.attr('type') == 'password' ? 'text' : 'password'
    el.attr('type',changeType);
}

async function changePassword(el)
{
    event.preventDefault()
    var data = {
        user_pass:$(el).find('#password').val(),
    }

    let request = await fetch('<?= route('change-password') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify(data),
    })

    let response = await request.json()

    if(response.status == false)
    {
        var msg = 'Terdapat error saat validasi data'
        if(response.msg)
            msg = response.msg
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
            footer: '<a href="javascript:void(0)">'+msg+'</a>'
        })
    }
    else
    {
        Swal.fire(
            'Saved!',
            'Password berhasil di ubah.',
            'success'
        )

        $(el).find('#password').val("")
    }
    return false;
}
</script>
</body>
</html>