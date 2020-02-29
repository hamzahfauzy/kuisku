<?php 
$this->title .= " | Users"; 
$this->visited = "users";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Users</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <div class="table-panel">
                    <div class="panel-content">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                    <div class="panel-content not-grow">
                        <div class="form-inline">
                            <label for="">Cari &nbsp;</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterPeserta(this.value)">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-peserta">
                    <tbody>
                        <tr>
                            <td colspan="3"><i>Tidak ada data!</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addParticipantForm" action="<?= route('master/users/insert') ?>">
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
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <select class="form-control" name="level" id="level" required>
                <option value="">- Pilih -</option>
                <?php foreach(['participant'=>'Participant','admin'=>'Admin','master'=>'Master'] as $key => $value): ?>
                <option value="<?= $key ?>"><?= $value ?></option>
                <?php endforeach ?>
                </select>
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
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editParticipantForm" action="<?= route('master/users/insert') ?>">
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
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <select class="form-control" name="level" id="level" required>
                <option value="">- Pilih -</option>
                <?php foreach(['participant'=>'Participant','admin'=>'Admin','master'=>'Master'] as $key => $value): ?>
                <option value="<?= $key ?>"><?= $value ?></option>
                <?php endforeach ?>
                </select>
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

async function loadData()
{
    let request = await fetch('<?= route('master/users/get') ?>')
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
        user_level:$('#addParticipantForm').find('#level').val(),
    }

    let request = await fetch('<?= route('master/users/insert') ?>',{
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
            'User berhasil disimpan.',
            'success'
        )
        document.getElementById('addParticipantForm').reset()
        dataPeserta = response
        fetchToTable()
    }

}

async function editPeserta()
{
    var data = {
        id:$('#editParticipantForm').find('#id').val(),
        user_name:$('#editParticipantForm').find('#name').val(),
        user_email:$('#editParticipantForm').find('#email').val(),
        user_pass:$('#editParticipantForm').find('#password').val(),
        user_level:$('#editParticipantForm').find('#level').val(),
    }

    let request = await fetch('<?= route('master/users/update') ?>',{
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
            'User berhasil diupdate.',
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
    $('.table-peserta > tbody').html('')
    if(data.length == 0)
    {
        $('.table-peserta > tbody').html('<tr><td colspan="2"><i>Tidak ada data!</i></td></tr>')
    }

    data.forEach(val => {
        $('.table-peserta > tbody').append(`<tr>
            <td>
                <b>${val.user_name}</b>
                (${val.user_level})
                <br>
                ${val.user_email}
                <br>
                <a href="javascript:void(0)" onclick="fetchEditPeserta(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deletePeserta(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function fetchEditPeserta(id)
{
    $('#editParticipantForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/master/users/find/'+id)
    let response = await request.json()
    $('#editParticipantForm').find('#id').val(response.id)
    $('#editParticipantForm').find('#name').val(response.user_name)
    $('#editParticipantForm').find('#email').val(response.user_email)
    $('#editParticipantForm').find('#level').val(response.user_level)
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
            let request = await fetch('<?= route('master/users/delete') ?>',{
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
                    'User berhasil dihapus.',
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

loadData()
</script>