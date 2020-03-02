<?php 
$this->title .= " | Kuis"; 
$this->visited = "kuis";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Kuis</h2>
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
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterKuis(this.value)">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-kuis">
                    <tbody>
                        <tr>
                            <td><i>Tidak ada data!</i></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kuis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addKuisForm" action="<?= route('admin/kuis/insert') ?>">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" rows="10" id="description" class="form-control editor"></textarea>
            </div>
            <div class="form-group">
                <label for="max_participant">Jumlah Peserta Per Sesi</label>
                <input type="number" name="max_participant" id="max_participant" class="form-control" required min="0">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="simpanKuis()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kuis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editKuisForm" action="<?= route('admin/kuis/insert') ?>">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control editor2"></textarea>
            </div>
            <div class="form-group">
                <label for="max_participant">Jumlah Peserta Per Sesi</label>
                <input type="number" name="max_participant" id="max_participant" class="form-control" required min="0">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="editKuis()"><i class="fa fa-save"></i> Update</button>
      </div>
    </div>
  </div>
</div>

<script async defer>
var dataKuis   = {};

async function loadData()
{
    let request = await fetch('<?= route('admin/kuis/get') ?>')
    let response = await request.json()
    dataKuis = response
    fetchToTable()
}

async function simpanKuis()
{
    var data = {
        post_title:$('#addKuisForm').find('#title').val(),
        post_content:$('#addKuisForm').find('#description').val(),
        max_participant:$('#addKuisForm').find('#max_participant').val(),
    }

    let request = await fetch('<?= route('admin/kuis/insert') ?>',{
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
            'Kuis berhasil disimpan.',
            'success'
        )
        document.getElementById('addKuisForm').reset()
        dataKuis = response
        fetchToTable()
    }

}

async function fetchEditKuis(id)
{
    $('#editKuisForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/admin/kuis/find/'+id)
    let response = await request.json()
    $('#editKuisForm').find('#id').val(response.id)
    $('#editKuisForm').find('#title').val(response.post_title)
    $('#editKuisForm').find('#description').val(response.post_content)
    $('#editKuisForm').find('#max_participant').val(response.max_participant)
}

async function editKuis()
{
    var data = {
        id:$('#editKuisForm').find('#id').val(),
        post_title:$('#editKuisForm').find('#title').val(),
        post_content:$('#editKuisForm').find('#description').val(),
        max_participant:$('#editKuisForm').find('#max_participant').val(),
    }

    let request = await fetch('<?= route('admin/kuis/update') ?>',{
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
            'Kuis berhasil diupdate.',
            'success'
        )
        document.getElementById('editKuisForm').reset()
        dataKuis = response
        fetchToTable()
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataKuis
    $('.table-kuis > tbody').html('')
    if(data.length == 0)
    {
        $('.table-kuis > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')
    }

    var no = 1;
    data.forEach(val => {
        $('.table-kuis > tbody').append(`<tr>
            <td width="10px">${no++}</td>
            <td>
                <b>${val.post_title}</b>
                <br>
                Jumlah Max. Peserta : ${val.max_participant ? val.max_participant : '-'} <br>
                <a href="<?= base_url() ?>/admin/kuis/view/${val.id}" class="act-btn jawaban-btn"><i class="fa fa-eye"></i> Detail</a> |
                <a href="javascript:void(0)" onclick="fetchEditKuis(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deleteKuis(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function deleteKuis(id)
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
            let request = await fetch('<?= route('admin/kuis/delete') ?>',{
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
                    'Kuis berhasil dihapus.',
                    'success'
                )
                dataKuis = response
                fetchToTable()
            }
        }
    })
}

function filterKuis(keyword)
{
    var data = dataKuis.filter(kuis => {
        return kuis.post_title.includes(keyword) || kuis.post_content.includes(keyword)
    })

    fetchToTable(data)
}

loadData()
</script>