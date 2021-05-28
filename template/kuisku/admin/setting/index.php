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
                        <input type="email" name="email" class="form-control" value="<?= $user->user_email ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Password <small>(kosongkan jika tidak di ubah)</small></label>
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
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

        <div class="col-sm-12 col-md-6">
            <div class="content-wrapper" style="margin-top:15px;">
                <div class="form-group">
                    <label for="">Logo Instansi / Perusahaan</label>
                    <input type="file" id="fileLogo" class="form-control" style="display:none;" onchange="uploadLogo(this)">
                    <br>
                    <?php if($customer->logo()): ?>
                    <img src="<?= $customer->logo->file_url ?>" alt="" width="250px" height="250px" style="object-fit:cover;"><br><br>
                    <button class="btn btn-success" onclick="fileLogo.click()"><i class="fa fa-cloud-upload"></i> Change Logo</button>
                    <?php else: ?>
                    <button class="btn btn-success" onclick="fileLogo.click()"><i class="fa fa-cloud-upload"></i> Upload Logo</button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadLogo(el)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan mengupload logo ini ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            var data = new FormData()
            data.append('file', el.files[0])

            let request = await fetch('<?= route('admin/setting/upload') ?>',{
                method :'POST',
                body   : data,
            })

            let response = await request.json()

            if(response.status == false)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href="javascript:void(0)">Terdapat kesalahan pada saat validasi</a>'
                })
            }
            else
            {
                Swal.fire(
                    'Uploaded!',
                    'Logo berhasil diupload.',
                    'success'
                )
                location=location
            }
        }
    })
}
</script>
