<?php 
$this->title .= " | Customers"; 
$this->visited = "customers";

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
                        <button class="btn btn-primary" onclick="loadAdmin()" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah</button>
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
        <table class="table table-bordered table-striped table-admin">
            <tbody>
                <tr>
                    <td colspan="3"><i>Tidak ada data!</i></td>
                </tr>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<script async defer>
var dataPeserta   = {};

async function loadData()
{
    let request = await fetch('<?= route('master/customers/'.$customer->id.'/get-users') ?>')
    let response = await request.json()
    dataPeserta = response.customer
    fetchToTable()
}

async function tambahkanUser(customer_id,user_id)
{
    var data = {
        customer_id:customer_id,
        user_id:user_id,
    }

    let request = await fetch('<?= route('master/customers/add-user') ?>',{
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
        loadAdmin()
        loadData()
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataPeserta
    $('.table-peserta > tbody').html('')
    if(data.users.length == 0)
    {
        $('.table-peserta > tbody').html('<tr><td colspan="2"><i>Tidak ada data!</i></td></tr>')
    }

    data.users.forEach(val => {
        $('.table-peserta > tbody').append(`<tr>
            <td>
                <b>${val.user.user_name}</b>
                <br>
                ${val.user.user_email}
                <br>
                <a href="javascript:void(0)" onclick="deletePeserta(${data.id},${val.user.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function loadAdmin()
{
    let request = await fetch('<?= route('master/customers/'.$customer->id.'/get-admin') ?>')
    let data = await request.json()
    $('.table-admin > tbody').html('')
    if(data.length == 0)
    {
        $('.table-admin > tbody').html('<tr><td colspan="2"><i>Tidak ada data!</i></td></tr>')
    }

    data.forEach(val => {
        $('.table-admin > tbody').append(`<tr>
            <td>
                <b>${val.user_name}</b>
                <br>
                ${val.user_email}
                <br>
                <a href="javascript:void(0)" onclick="tambahkanUser(<?=$customer->id?>,${val.id})" class="act-btn jawaban-btn"><i class="fa fa-check"></i> Tambahkan</a>
            </td>
        </tr>`)
    })
}

async function deletePeserta(customer_id, user_id)
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
            let request = await fetch('<?= route('master/customers/remove-user') ?>',{
                method :'POST',
                headers : {
                    'Content-Type':'application/json'
                },
                body   : JSON.stringify({
                    customer_id:customer_id,
                    user_id:user_id
                }),
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
                dataPeserta = response.customer
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