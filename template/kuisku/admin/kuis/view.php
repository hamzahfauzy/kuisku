<?php 
$this->title .= " | Kuis"; 
$this->visited = "kuis";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<input type="file" name="file" id="import_file" accept=".xls,.xlsx,.csv,.ods" onchange="importParticipant(this)" style="display:none;">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Detail Kuis (<?= $kuis->post_title ?>)</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <div class="table-panel">
                    <div class="panel-content">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus fa-fw"></i> Tambah Sesi</button>
                        <button class="btn btn-success" onclick="fetchKategori()" data-toggle="modal" data-target="#modalKategori"><i class="fa fa-list fa-fw"></i> Kategori Soal</button>
                        <button class="btn btn-danger" id="btn-import" onclick="import_file.click()"><i class="fa fa-upload"></i> Import Peserta</button>
                        <a href="<?= route('admin/kuis/view/'.$kuis->id.'/scoreboard') ?>" class="btn btn-warning"><i class="fa fa-file-text fa-fw"></i> Scoreboard</a>
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
        <h5 class="modal-title" id="exampleModalLabel">Tambah Sesi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addKuisForm" action="<?= route('admin/kuis/sesi/insert') ?>">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" rows="10" id="description" class="form-control editor"></textarea>
            </div>
            <div class="form-group">
                <label for="waktuMulai">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control">
            </div>
            <div class="form-group">
                <label for="waktuSelesai">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="form-control">
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Sesi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editKuisForm" action="<?= route('admin/kuis/sesi/insert') ?>">
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
                <label for="waktuMulai">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control">
            </div>
            <div class="form-group">
                <label for="waktuSelesai">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="form-control">
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

<!-- Modal Peserta -->
<div class="modal fade" id="modalPeserta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Peserta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                <h3>Peserta</h3>
                <table class="table table-bordered table-striped table-peserta">
                    <tbody>
                        <tr>
                            <td colspan="3"><i>Tidak ada data!</i></td>
                        </tr>
                    </tbody>
                </table>
                </div>

                <div class="col-sm-12 col-md-6">
                <h3>Calon Peserta</h3>
                <table class="table table-bordered table-striped table-calon-peserta">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Soal -->
<div class="modal fade" id="modalSoal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                <h3>Soal</h3>
                <table class="table table-bordered table-striped table-soal">
                    <tbody>
                        <tr>
                            <td><i>Tidak ada data!</i></td>
                        </tr>
                    </tbody>
                </table>
                </div>

                <div class="col-sm-12 col-md-6">
                <h3>Koleksi Soal</h3>
                <div class="form-group">
                    <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterSoal(this.value)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <p></p>
                <table class="table table-bordered table-striped table-koleksi-soal">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Kategori Soal -->
<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kategori Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= route('admin/kuis/save-category') ?>" onsubmit="saveCategory(this)">
      <input type="hidden" name="kuis_id" value="<?= $kuis->id ?>">
      <div class="modal-body">
        <table class="table table-bordered table-striped table-kategori">
            <tbody>
                <tr>
                    <td><i>Tidak ada data!</i></td>
                </tr>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script async defer>
var dataKuis   = {};
var dataSoal   = {};

async function loadData()
{
    let request = await fetch('<?= route('admin/kuis/sesi/get/'.$kuis->id) ?>')
    let response = await request.json()
    dataKuis = response
    fetchToTable()
}

async function fetchSoal()
{
    let request = await fetch('<?= route('admin/kuis/soal/get/'.$kuis->id) ?>')
    let response = await request.json()

    dataSoal = response

    $('.table-soal > tbody').html('')
    $('.table-koleksi-soal > tbody').html('')
    
    if(response.kuis.soal.length == 0)
        $('.table-soal > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')

    if(response.allSoal.length == 0)
        $('.table-koleksi-soal > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')

    response.allSoal.forEach(val => {
        var categories = val.categories.map(e => e.category.category_name ).join(', ')
        $('.table-koleksi-soal > tbody').append(`<tr>
            <td>
                <b>${val.post_title}</b><br>
                ${val.post_excerpt}<br>
                <div class="post-tag">
                    <i class="fa fa-tag"></i> ${categories}
                </div>
                <a href="javascript:void(0)" onclick="tambahkanSoal(<?=$kuis->id?>,${val.id})" class="act-btn jawab-btn"><i class="fa fa-arrow-right"></i> Tambah Soal</a>
            </td>
        </tr>`)
    })

    response.kuis.soal.forEach(val => {
        var categories = val.soal.categories.map(e => e.category.category_name ).join(', ')
        $('.table-soal > tbody').append(`<tr>
            <td>
                <b>${val.soal.post_title}</b><br>
                ${val.soal.post_excerpt}<br>
                <div class="post-tag">
                    <i class="fa fa-tag"></i> ${categories}
                </div>
                <a href="javascript:void(0)" onclick="hapusSoal(<?=$kuis->id?>,${val.post_question_id})" class="act-btn delete-btn"><i class="fa fa-close"></i> Batal</a>
            </td>
        </tr>`)
    })
}

async function filterSoal(keyword)
{
    keyword = keyword.toLowerCase()
    var response = dataSoal

    $('.table-koleksi-soal > tbody').html('')
    
    var filterSoal = response.allSoal.filter(soal => {
        var categories = soal.categories.map(e => e.category.category_name ).join(', ')
        return soal.post_title.toLowerCase().includes(keyword) || soal.post_content.toLowerCase().includes(keyword) || categories.toLowerCase().includes(keyword)
    })

    if(filterSoal.length == 0)
        $('.table-koleksi-soal > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')

    filterSoal.forEach(val => {
        var categories = val.categories.map(e => e.category.category_name ).join(', ')
        $('.table-koleksi-soal > tbody').append(`<tr>
            <td>
                <b>${val.post_title}</b><br>
                ${val.post_excerpt}<br>
                <div class="post-tag">
                    <i class="fa fa-tag"></i> ${categories}
                </div>
                <a href="javascript:void(0)" onclick="tambahkanSoal(<?=$kuis->id?>,${val.id})" class="act-btn jawab-btn"><i class="fa fa-arrow-right"></i> Tambah Soal</a>
            </td>
        </tr>`)
    })
}

async function simpanKuis()
{
    var data = {
        post_title:$('#addKuisForm').find('#title').val(),
        post_content:$('#addKuisForm').find('#description').val(),
        waktu_mulai:$('#addKuisForm').find('#waktu_mulai').val(),
        waktu_selesai:$('#addKuisForm').find('#waktu_selesai').val(),
    }

    let request = await fetch('<?= route('admin/kuis/sesi/insert/'.$kuis->id) ?>',{
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
            'Sesi berhasil disimpan.',
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
    let request = await fetch('<?= base_url() ?>/admin/kuis/sesi/find/'+id)
    let response = await request.json()
    $('#editKuisForm').find('#id').val(response.id)
    $('#editKuisForm').find('#title').val(response.post_title)
    $('#editKuisForm').find('#description').val(response.post_content)
    $('#editKuisForm').find('#waktu_mulai').val(response.meta.waktu_mulai)
    $('#editKuisForm').find('#waktu_selesai').val(response.meta.waktu_selesai)
}

async function fetchPeserta(id)
{
    $('.table-peserta > tbody').html('<tr><td><i>Loading...</i></td></tr>')
    $('.table-calon-peserta > tbody').html('<tr><td><i>Loading...</i></td></tr>')

    let request = await fetch('<?= base_url() ?>/admin/kuis/sesi/view/'+id)
    let response = await request.json()

    $('.table-peserta > tbody').html('')
    $('.table-calon-peserta > tbody').html('')
    if(response.sesi.peserta.length == 0)
        $('.table-peserta > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')

    if(response.exclude.length == 0)
        $('.table-calon-peserta > tbody').html('<tr><td><i>Tidak ada data!</i></td></tr>')

    var no = 1;

    if(response.sesi.waktu_mulai == 0 && response.sesi.waktu_selesai == 0)
    {
        response.sesi.peserta.forEach(val => {
            $('.table-peserta > tbody').append(`<tr>
                <td>
                    <b>${val.user.user_name}</b>
                    <br>
                    ${val.user.user_email}
                    <br>
                    <a href="javascript:void(0)" onclick="batalkanPeserta(${id},${val.user.id},this)" class="act-btn delete-btn"><i class="fa fa-close"></i> Batal</a>
                    <a href="javascript:void(0)" onclick="kirimNotifikasi(${id},${val.user.id},this)" class="act-btn jawab-btn"><i class="fa fa-send"></i> Kirim Notifikasi</a>
                </td>
            </tr>`)
        })

        response.exclude.forEach(val => {
            $('.table-calon-peserta > tbody').append(`<tr>
                <td>
                    <b>${val.user_name}</b>
                    <br>
                    ${val.user_email}
                    <br>
                    <a href="javascript:void(0)" onclick="jadikanPeserta(${id},${val.id},this)" class="act-btn jawab-btn"><i class="fa fa-arrow-right"></i> Jadikan Peserta</a>
                </td>
            </tr>`)
        })
    }
    else
    {
        var waktu_mulai   = new Date(response.sesi.waktu_mulai)
        var waktu_selesai = new Date(response.sesi.waktu_selesai)
        var now           = new Date(response.sesi.now)
        if(now < waktu_mulai)
        {
            response.sesi.peserta.forEach(val => {
                $('.table-peserta > tbody').append(`<tr>
                    <td>
                        <b>${val.user.user_name}</b>
                        <br>
                        ${val.user.user_email}
                        <br>
                        <a href="javascript:void(0)" onclick="batalkanPeserta(${id},${val.user.id},this)" class="act-btn delete-btn"><i class="fa fa-close"></i> Batal</a>
                        <a href="javascript:void(0)" onclick="kirimNotifikasi(${id},${val.user.id},this)" class="act-btn jawab-btn"><i class="fa fa-send"></i> Kirim Notifikasi</a>
                    </td>
                </tr>`)
            })

            response.exclude.forEach(val => {
                $('.table-calon-peserta > tbody').append(`<tr>
                    <td>
                        <b>${val.user_name}</b>
                        <br>
                        ${val.user_email}
                        <br>
                        <a href="javascript:void(0)" onclick="jadikanPeserta(${id},${val.id},this)" class="act-btn jawab-btn"><i class="fa fa-arrow-right"></i> Jadikan Peserta</a>
                    </td>
                </tr>`)
            })
        }
        else
        {
            response.sesi.peserta.forEach(val => {
                $('.table-peserta > tbody').append(`<tr>
                    <td>
                        <b>${val.user.user_name}</b>
                        <br>
                        ${val.user.user_email}
                    </td>
                </tr>`)
            })

            response.exclude.forEach(val => {
                $('.table-calon-peserta > tbody').append(`<tr>
                    <td>
                        <b>${val.user_name}</b>
                        <br>
                        ${val.user_email}
                    </td>
                </tr>`)
            })
        }
    }
}

async function editKuis()
{
    var data = {
        id:$('#editKuisForm').find('#id').val(),
        post_title:$('#editKuisForm').find('#title').val(),
        post_content:$('#editKuisForm').find('#description').val(),
        waktu_mulai:$('#editKuisForm').find('#waktu_mulai').val(),
        waktu_selesai:$('#editKuisForm').find('#waktu_selesai').val(),
    }

    let request = await fetch('<?= route('admin/kuis/sesi/update') ?>',{
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
            'Sesi berhasil diupdate.',
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

        if(val.waktu_mulai == 0 && val.waktu_selesai == 0)
        {
            $('.table-kuis > tbody').append(`<tr>
                <td width="10px">${no++}</td>
                <td>
                    <b>${val.post_title}</b>
                    <br>
                    Mulai : ${val.waktu_mulai}<br>
                    Selesai : ${val.waktu_selesai}<br>
                    Jumlah Peserta : ${val.jumlah_peserta}<br>
                    <a href="javascript:void(0)" onclick="fetchPeserta(${val.id})" class="act-btn jawaban-btn" class="act-btn edit-btn" data-toggle="modal" data-target="#modalPeserta"><i class="fa fa-eye"></i> Peserta</a> |
                    <a href="javascript:void(0)" onclick="fetchEditKuis(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                    <a href="javascript:void(0)" onclick="deleteKuis(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
                </td>
            </tr>`)
        }
        else
        {

            var waktu_mulai   = new Date(val.waktu_mulai)
            var waktu_selesai = new Date(val.waktu_selesai)
            var now           = new Date(val.now)

            if(now < waktu_mulai)
            $('.table-kuis > tbody').append(`<tr>
                <td width="10px">${no++}</td>
                <td>
                    <b>${val.post_title}</b>
                    <br>
                    Mulai : ${val.waktu_mulai}<br>
                    Selesai : ${val.waktu_selesai}<br>
                    Jumlah Peserta : ${val.jumlah_peserta}<br>
                    <a href="javascript:void(0)" onclick="fetchPeserta(${val.id})" class="act-btn jawaban-btn" class="act-btn edit-btn" data-toggle="modal" data-target="#modalPeserta"><i class="fa fa-eye"></i> Peserta</a> |
                    <a href="javascript:void(0)" onclick="fetchEditKuis(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                    <a href="javascript:void(0)" onclick="deleteKuis(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
                </td>
            </tr>`)
            else
            $('.table-kuis > tbody').append(`<tr>
                <td width="10px">${no++}</td>
                <td>
                    <b>${val.post_title}</b>
                    <br>
                    Mulai : ${val.waktu_mulai}<br>
                    Selesai : ${val.waktu_selesai}<br>
                    Jumlah Peserta : ${val.jumlah_peserta}<br>
                    <a href="javascript:void(0)" onclick="fetchPeserta(${val.id})" class="act-btn jawaban-btn" class="act-btn edit-btn" data-toggle="modal" data-target="#modalPeserta"><i class="fa fa-eye"></i> Peserta</a> |
                    <a href="javascript:void(0)" onclick="deleteKuis(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
                </td>
            </tr>`)
        }
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
            let request = await fetch('<?= route('admin/kuis/sesi/delete') ?>',{
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
                    'Sesi berhasil dihapus.',
                    'success'
                )
                dataKuis = response
                fetchToTable()
            }
        }
    })
}

async function jadikanPeserta(sesi_id, user_id, el)
{
    el.innerHTML = "Loading..."
    el.removeAttribute("onclick")
    let request = await fetch('<?= route('admin/kuis/sesi/jadi-peserta') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify({sesi_id:sesi_id,user_id:user_id}),
    })

    let response = await request.json()
    if(response.status == false)
    {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Gagal menjadikan peserta!',
            footer: '<a href="javascript:void(0)">Peserta sudah mencapai batas maksimal</a>'
        })
    }
    else
        fetchPeserta(sesi_id)
}

async function batalkanPeserta(sesi_id, user_id, el)
{
    el.innerHTML = "Loading..."
    el.removeAttribute("onclick")
    let request = await fetch('<?= route('admin/kuis/sesi/batal-peserta') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify({sesi_id:sesi_id,user_id:user_id}),
    })
    let response = await request.json()
    fetchPeserta(sesi_id)
}

async function kirimNotifikasi(sesi_id, user_id, el)
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
            // el.removeAttribute("onclick")
            let request = await fetch('<?= route('admin/kuis/sesi/notifikasi-peserta') ?>',{
                method :'POST',
                headers : {
                    'Content-Type':'application/json'
                },
                body   : JSON.stringify({sesi_id:sesi_id,user_id:user_id}),
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
            }

            el.innerHTML = '<i class="fa fa-send"></i> Kirim Notifikasi'
        }
    })
}

async function tambahkanSoal(kuis_id, soal_id)
{
    let request = await fetch('<?= route('admin/kuis/soal/tambah-soal') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify({kuis_id:kuis_id,soal_id:soal_id}),
    })

    let response = await request.json()
    fetchSoal()
}

async function hapusSoal(kuis_id, soal_id)
{
    let request = await fetch('<?= route('admin/kuis/soal/hapus-soal') ?>',{
        method :'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body   : JSON.stringify({kuis_id:kuis_id,soal_id:soal_id}),
    })

    let response = await request.json()
    fetchSoal()
}

function filterKuis(keyword)
{
    var data = dataKuis.filter(kuis => {
        return kuis.post_title.includes(keyword) || kuis.post_content.includes(keyword)
    })

    fetchToTable(data)
}

async function fetchKategori()
{
    let request = await fetch('<?= route('admin/category/get') ?>')
    let response = await request.json()

    let request2 = await fetch('<?= route('admin/kuis/category/'.$kuis->id) ?>')
    let response2 = await request2.json()

    var data = response
    $('.table-kategori').html('')
    if(data.length == 0)
    {
        $('.table-kategori').html('<tr><td><i>Tidak ada data!</i></td></tr>')
    }

    data.forEach(val => {
        var category = response2.find(cat => cat.category_id === val.id)
        category = category && category.jumlah_soal ? category.jumlah_soal : 0
        $('.table-kategori').append(`<tr>
            <td>
                <b>${val.category_name}</b>
                <p>${val.category_description}</p>
            </td>
            <td>
                <div class="form-group">
                    <label>Jumlah Soal</label>
                    <input type="number" min="0" value="${category}" name="category_setting[${val.id}]" id="category_setting_${val.id}" class="form-control">
                </div>
            </td>
        </tr>`)
    })
}

async function saveCategory(form)
{
    event.preventDefault()
    var data = $(form).serialize()
    data = decodeURI(data)
    let request = await fetch($(form).attr('action'), {
        method: 'post',
        body: data,
        headers: { 'Content-type': 'application/x-www-form-urlencoded' }
    })

    let response = await request.json()
    Swal.fire(
        'Saved!',
        'Kategori berhasil disimpan.',
        'success'
    )
}

async function importParticipant(el)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan mengimport data peserta ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            var btnImport = document.querySelector('#btn-import');
            btnImport.innerHTML = "Mengimport..."
            var data = new FormData()
            data.append('id', '<?= $kuis->id ?>')
            data.append('file', el.files[0])

            let request = await fetch('<?= route('admin/kuis/import-participant') ?>',{
                method :'POST',
                body   : data,
            })

            el.value = ''

            let response = await request.json()

            if(response.status == false)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href="javascript:void(0)">Terdapat kesalahan pada saat validasi</a>'
                })

                btnImport.innerHTML = "<i class='fa fa-upload'></i> Import Soal"
            }
            else
            {
                Swal.fire(
                    'Imported!',
                    'Peserta Berhasil di import.',
                    'success'
                )

                btnImport.innerHTML = "<i class='fa fa-upload'></i> Import Peserta"
                loadData()
            }
        }
    })
}

loadData()
</script>