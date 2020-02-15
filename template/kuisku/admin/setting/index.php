<?php 
$this->title .= " | Pengaturan"; 
$this->visited = "pengaturan";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Pengaturan</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <form method="post" action="<?= route('admin/setting/update') ?>">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $user->user_name ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <input type="email" name="email" class="form-control" value="<?= $user->user_email ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">Password <small>(kosongkan jika tidak di ubah)</small></label>
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="content-wrapper" style="margin-top:15px;">
            <?php $customer = $user->customer() ?>
                <div class="form-group">
                    <label for="">Nama Instansi / Perusahaan</label>
                    <input type="text" class="form-control" value="<?= $customer->nama ?>" readonly="">
                </div>
                <div class="form-group">
                    <label for="">E-Mail</label>
                    <input type="email" class="form-control" value="<?= $customer->email ?>" readonly="">
                </div>
                <div class="form-group">
                    <label for="">No Telepon</label>
                    <input type="tel" class="form-control" value="<?= $customer->no_telepon ?>" readonly="">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea class="form-control" readonly=""><?= $customer->alamat ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
