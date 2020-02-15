<?php 
$this->title .= " | Kategori"; 
$this->visited = "kategori";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Kategori</h2>
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
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterKategori(this.value)">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-kategori">
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addCategoryForm" action="<?= route('admin/category/insert') ?>">
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Deskripsi</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="simpanKategori()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editCategoryForm" action="<?= route('admin/category/insert') ?>">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Deskripsi</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="editKategori()"><i class="fa fa-save"></i> Update</button>
      </div>
    </div>
  </div>
</div>

<script defer>
var dataKategori = {};
async function loadData()
{
    let request = await fetch('<?= route('master/category/get') ?>')
    let response = await request.json()
    dataKategori = response
    fetchToTable()
}

async function simpanKategori()
{
    var data = {
        category_name:$('#addCategoryForm').find('input#name').val(),
        category_description:$('#addCategoryForm').find('textarea#description').val(),
    }

    let request = await fetch('<?= route('master/category/insert') ?>',{
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
            'Kategori berhasil disimpan.',
            'success'
        )
        document.getElementById('addCategoryForm').reset()
        dataKategori = response
        fetchToTable()
    }

}

async function editKategori()
{

    var data = {
        id:$('#editCategoryForm').find('#id').val(),
        category_name:$('#editCategoryForm').find('#name').val(),
        category_description:$('#editCategoryForm').find('#description').val(),
    }

    let request = await fetch('<?= route('master/category/update') ?>',{
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
            'Kategori berhasil diupdate.',
            'success'
        )
        document.getElementById('editCategoryForm').reset()
        dataKategori = response
        fetchToTable()
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataKategori
    $('.table-kategori').html('')
    if(data.length == 0)
    {
        $('.table-kategori').html('<tr><td><i>Tidak ada data!</i></td></tr>')
    }

    data.forEach(val => {
        $('.table-kategori').append(`<tr>
            <td>
                <b>${val.category_name}</b>
                <p>${val.category_description}</p>
                <a href="javascript:void(0)" onclick="fetchEditKategori(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deleteKategori(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function fetchEditKategori(id)
{
    $('#editCategoryForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/master/category/find/'+id)
    let response = await request.json()
    $('#editCategoryForm').find('#id').val(response.id)
    $('#editCategoryForm').find('#name').val(response.category_name)
    $('#editCategoryForm').find('#description').val(response.category_description)
}

async function deleteKategori(id)
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
            let request = await fetch('<?= route('master/category/delete') ?>',{
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
                    footer: '<a href="javascript:void(0)">Kategori gagal di hapus</a>'
                })
            }
            else
            {
                Swal.fire(
                    'Deleted!',
                    'Kategori berhasil dihapus.',
                    'success'
                )
                dataKategori = response
                fetchToTable()
            }
        }
    })
}

function filterKategori(keyword)
{
    var data = dataKategori.filter(kategori => {
        return kategori.category_name.includes(keyword) || kategori.category_description.includes(keyword)
    })

    fetchToTable(data)
}

loadData()
</script>