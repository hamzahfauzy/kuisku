<?php 
$this->title .= " | Peserta"; 
$this->visited = "peserta";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<form action="<?= route('admin/participant/import') ?>" id="formImport" method="post" enctype="multipart/form-data" style="display:none;">
<input type="file" name="file" id="import_file" accept=".xls,.xlsx" onchange="formImport.submit()">
</form>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Peserta</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <div class="table-panel">
                    <div class="panel-content">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah</button>
                        <button class="btn btn-success" onclick="import_file.click()"><i class="fa fa-upload"></i> Import</button>
                    </div>
                    <div class="panel-content not-grow">
                        <div class="form-inline">
                            <label for="">Cari &nbsp;</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterPeserta(this.value)">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-peserta">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Peserta</td>
                            <td>Email</td>
                            <td>No. Handphone</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5"><i>Tidak ada data!</i></td>
                        </tr>
                    </tbody>
                </table>
                <p>Total Jumlah Peserta : <?= $count_of_participant ?></p>
                <ul class="pagination"> 
                    <!-- Declare the item in the group -->
                    <li class="page-item"> 
                        <!-- Declare the link of the item -->
                        <a class="page-link" href="<?= base_url() ?>/admin/participant?page=<?=isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page']-1 : 1?>">Previous</a> 
                    </li>

                    <?php 
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
                    $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link number
                    $end_number = ($page < ($num_of_page - $jumlah_number))? $page + $jumlah_number : $num_of_page; //
                    for($i=$start_number;$i<=$end_number;$i++): 
                        $link_active = ($page == $i)? 'pagination-active' : '';
                    ?>
                    <!-- Rest of the pagination items -->
                    <li class="page-item"> 
                        <a class="page-link <?= $link_active ?>" href="<?= base_url() ?>/admin/participant?page=<?=$i?>"><?= $i ?></a> 
                    </li> 
                    <?php endfor ?>
                    <li class="page-item"> 
                        <a class="page-link" href="<?= base_url() ?>/admin/participant?page=<?= isset($_GET['page']) && $_GET['page'] == $num_of_page ? $num_of_page : $_GET['page']+1?>">Next</a> 
                    </li> 
                </ul> 
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Peserta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addParticipantForm" action="<?= route('admin/participant/insert') ?>">
            <div class="form-group">
                <label for="title">Nama</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">E-Mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text" onclick="showPassword('#addParticipantForm')">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="input-group-text" onclick="generatePassword('#addParticipantForm')">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="no_hp">No. Handphone</label>
                <input type="tel" name="no_hp" id="no_hp" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="simpanPeserta()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Peserta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editParticipantForm" action="<?= route('admin/question/insert') ?>">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="title">Nama</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">E-Mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password <small>(Kosongkan jika tidak di update)</small></label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text" onclick="showPassword('#editParticipantForm')">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="input-group-text" onclick="generatePassword('#editParticipantForm')">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="no_hp">No. Handphone</label>
                <input type="tel" name="no_hp" id="no_hp" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="editPeserta()"><i class="fa fa-save"></i> Update</button>
      </div>
    </div>
  </div>
</div>

<script async defer>
var dataPeserta   = {};

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

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

async function loadData(page = 1)
{
    page = getParameterByName('page') ? getParameterByName('page') : page
    $('.table-peserta > tbody').html('<tr><td colspan="5"><i>Loading...</i></td></tr>')
    let request = await fetch('<?= route('admin/participant/get') ?>?page='+page)
    let response = await request.json()
    dataPeserta = response
    fetchToTable()
}

async function simpanPeserta()
{
    var data = {
        user_name:$('#addParticipantForm').find('#name').val(),
        user_email:$('#addParticipantForm').find('#email').val(),
        user_pass:$('#addParticipantForm').find('#password').val(),
        no_hp:$('#addParticipantForm').find('#no_hp').val(),
    }

    let request = await fetch('<?= route('admin/participant/insert') ?>',{
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
            'Peserta berhasil disimpan.',
            'success'
        )
        document.getElementById('addParticipantForm').reset()
        dataPeserta = response
        fetchToTable()
    }

}

async function fetchEditPeserta(id)
{
    $('#editParticipantForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/admin/participant/find/'+id)
    let response = await request.json()
    $('#editParticipantForm').find('#id').val(response.id)
    $('#editParticipantForm').find('#name').val(response.user_name)
    $('#editParticipantForm').find('#email').val(response.user_email)
    $('#editParticipantForm').find('#no_hp').val(response.no_hp)
}

async function editPeserta()
{
    var data = {
        id:$('#editParticipantForm').find('#id').val(),
        user_name:$('#editParticipantForm').find('#name').val(),
        user_email:$('#editParticipantForm').find('#email').val(),
        user_pass:$('#editParticipantForm').find('#password').val(),
        no_hp:$('#editParticipantForm').find('#no_hp').val(),
    }

    let request = await fetch('<?= route('admin/participant/update') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify(data),
    })

    let response = await request.json()

    if(response.status == false)
    {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
            footer: '<a href="javascript:void(0)">Terdapat error saat validasi data</a>'
        })
    }
    else
    {
        Swal.fire(
            'Saved!',
            'Peserta berhasil diupdate.',
            'success'
        )
        document.getElementById('editParticipantForm').reset()
        dataPeserta = response
        fetchToTable()
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataPeserta
    $('.table-peserta > tbody').html('<tr><td colspan="5"><i>Loading...</i></td></tr>')
    if(data.length == 0)
    {
        $('.table-peserta > tbody').html('<tr><td colspan="5"><i>Tidak ada data!</i></td></tr>')
    }
    else
    {
        $('.table-peserta > tbody').html('')
    }

    var no = 1
    data.forEach(val => {
        $('.table-peserta > tbody').append(`<tr>
            <td>${no++}</td>
            <td>
                <b>${val.user_name}</b>
            </td>
            <td>
                ${val.user_email}
            </td>
            <td>
                ${val.no_hp}
            </td>
            <td>
                <a href="javascript:void(0)" onclick="kirimNotifikasi(${val.id},this)" class="act-btn jawab-btn"><i class="fa fa-send"></i> Kirim Notifikasi</a> |
                <a href="javascript:void(0)" onclick="fetchEditPeserta(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deletePeserta(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function kirimNotifikasi(user_id, el)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan mengirim notifikasi kepada peserta ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            el.innerHTML = "Loading..."
            el.removeAttribute("onclick")
            let request = await fetch('<?= route('admin/participant/notifikasi-peserta') ?>',{
                method :'POST',
                headers : {
                    'Content-Type':'application/json'
                },
                body   : JSON.stringify({user_id:user_id}),
            })
            let response = await request.json()

            if(response.status == false)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Notifikasi gagal terkirim!',
                    footer: '<a href="javascript:void(0)">Terdapat kesalahan pada saat pengiriman sms</a>'
                })
            }
            else
            {
                Swal.fire(
                    'Success!',
                    'Notifikasi Berhasil di kirim.',
                    'success'
                )

                loadData()
            }
        }
    })
}

async function fetchEditPeserta(id)
{
    $('#editParticipantForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/admin/participant/find/'+id)
    let response = await request.json()
    $('#editParticipantForm').find('#id').val(response.id)
    $('#editParticipantForm').find('#name').val(response.user_name)
    $('#editParticipantForm').find('#email').val(response.user_email)
    $('#editParticipantForm').find('#no_hp').val(response.no_hp)
}

async function deletePeserta(id)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan menghapus data ini ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            let request = await fetch('<?= route('admin/participant/delete') ?>',{
                method :'POST',
                headers : {
                    'Content-Type':'application/json'
                },
                body   : JSON.stringify({id:id}),
            })

            let response = await request.json()

            if(response.status == false)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href="javascript:void(0)">Soal gagal di hapus</a>'
                })
            }
            else
            {
                Swal.fire(
                    'Deleted!',
                    'Peserta berhasil dihapus.',
                    'success'
                )
                dataPeserta = response
                fetchToTable()
            }
        }
    })
}

function filterPeserta(keyword)
{
    var data = dataPeserta.filter(peserta => {
        return peserta.user_name.includes(keyword) || peserta.user_email.includes(keyword)
    })

    fetchToTable(data)
}

window.onload = function(){
    loadData()
}
</script>