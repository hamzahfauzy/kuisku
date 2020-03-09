<?php 
$this->title .= " | Soal"; 
$this->visited = "soal";

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
    asset('js/ckeditor/ckeditor.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
<input type="file" name="file" id="import_file" accept=".xls,.xlsx,.csv,.ods" onchange="importSoal(this)" style="display:none;">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Soal</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <div class="table-panel">
                    <div class="panel-content">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah</button>
                        <button class="btn btn-danger" id="btn-import" onclick="import_file.click()"><i class="fa fa-upload"></i> Import Soal</button>
                    </div>
                    <div class="panel-content not-grow">
                        <div class="form-inline">
                            <label for="">Cari &nbsp;</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterSoal(this.value)">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-soal">
                    <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th>Soal</th>
                            <th>Kategori</th>
                            <th>Jawaban</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5"><i>Tidak ada data!</i></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addQuestionForm" action="<?= route('admin/question/insert') ?>">
            <div class="form-group">
                <!-- <label for="title">Judul</label> -->
                <input type="hidden" value="Post Soal" name="title" id="title">
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="editor" rows="10" id="editor" class="form-control editor"></textarea>
            </div>
            <div class="form-group">
                <label for="category">Kategori</label>
                <select name="category" id="category" class="form-control" required multiple>
                    <?php if(empty($categories)): ?>
                    <option value="">Tidak ada Kategori</option>
                    <?php endif ?>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= $category->category_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="simpanSoal()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editQuestionForm" action="<?= route('admin/question/insert') ?>">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <!-- <label for="title">Judul</label> -->
                <input type="hidden" value="Post Soal" name="title" id="title">
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="editor2" id="editor2" class="form-control editor2"></textarea>
            </div>
            <div class="form-group">
                <label for="category">Kategori</label>
                <select name="category" id="category" class="form-control" required multiple>
                    <?php if(empty($categories)): ?>
                    <option value="">Tidak ada Kategori</option>
                    <?php endif ?>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= $category->category_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="button" class="btn btn-primary" onclick="editSoal()"><i class="fa fa-save"></i> Update</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Jawaban -->
<div class="modal fade" id="modalJawaban" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Jawaban</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="answerForm" action="<?= route('admin/question/insert') ?>">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="jawaban_id" id="jawaban_id" value="0">
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="editor3" id="editor3" class="form-control editor3"></textarea>
            </div>
            <div class="form-group">
                <label for="skor">Skor</label>
                <input type="number" class="form-control" name="skor" id="skor" min="0" value="0">
            </div>
        </form>
        <div class="table-panel">
            <div class="panel-content">
                <button class="btn btn-primary" onclick="saveAnswer()"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </div>
        <table class="table table-bordered table-striped table-jawaban">
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
var dataSoal   = {};
var dataAnswer = {};

CKEDITOR.replace( 'editor', {
  height: 150,
  filebrowserUploadUrl: "<?=base_url()?>/admin/question/image-upload",
  filebrowserUploadMethod:"form"
});

CKEDITOR.replace( 'editor2', {
  height: 150,
  filebrowserUploadUrl: "<?=base_url()?>/admin/question/image-upload",
  filebrowserUploadMethod:"form"
});

CKEDITOR.replace( 'editor3' , {
  height: 100,
  filebrowserUploadUrl: "<?=base_url()?>/admin/question/image-upload",
  filebrowserUploadMethod:"form"
});

async function loadData()
{
    $('.table-soal > tbody').html('<tr><td colspan="5"><i>Loading...</i></td></tr>')
    let request = await fetch('<?= route('admin/question/get') ?>')
    let response = await request.json()
    dataSoal = response.questions
    fetchToTable()
}

async function simpanSoal()
{
    var categories = [];
    $('#addQuestionForm #category option:selected').each(function() {
        categories.push($(this).val())
    });

    var data = {
        post_title:$('#addQuestionForm').find('#title').val(),
        // post_content:$('#addQuestionForm').find('#description').val(),
        post_content:CKEDITOR.instances.editor.getData(),
        categories:categories,
    }

    let request = await fetch('<?= route('admin/question/insert') ?>',{
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
            'Soal berhasil disimpan.',
            'success'
        )
        document.getElementById('addQuestionForm').reset()
        CKEDITOR.instances.editor.setData('')
        dataSoal = response.questions
        fetchToTable()
    }

}

async function editSoal()
{
    var categories = [];
    $('#editQuestionForm #category option:selected').each(function() {
        categories.push($(this).val())
    });

    var data = {
        id:$('#editQuestionForm').find('#id').val(),
        post_title:$('#editQuestionForm').find('#title').val(),
        // post_content:$('#editQuestionForm').find('#description').val(),
        post_content:CKEDITOR.instances.editor2.getData(),
        categories:categories,
    }

    let request = await fetch('<?= route('admin/question/update') ?>',{
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
            'Soal berhasil diupdate.',
            'success'
        )
        document.getElementById('editQuestionForm').reset()
        $('#editQuestionForm #category option').each(function(){
            $(this).removeAttr('selected')
        })
        CKEDITOR.instances.editor2.setData('')
        dataSoal = response.questions
        fetchToTable()
    }

}

async function saveAnswer()
{
    var data = {
        post_parent_id:$('#answerForm').find('#id').val(),
        jawaban_id:$('#answerForm').find('#jawaban_id').val(),
        // post_content:$('#answerForm').find('#description').val(),
        post_content:CKEDITOR.instances.editor3.getData(),
        skor:$('#skor').val(),
    }

    let request = await fetch('<?= route('admin/question/answer/insert') ?>',{
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
            'Jawaban berhasil disimpan.',
            'success'
        )
        $('#answerForm').find('#jawaban_id').val(0)
        $('#skor').val("")
        CKEDITOR.instances.editor3.setData('')
        await fetchJawaban($('#answerForm').find('#id').val())
        await loadData()
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataSoal
    $('.table-soal > tbody').html('')
    if(data.length == 0)
    {
        $('.table-soal > tbody').html('<tr><td colspan="5"><i>Tidak ada data!</i></td></tr>')
    }

    var no = 1;
    data.forEach(val => {
        var categories = val.categories.map(e => e.category.category_name ).join(', ')
        var answer = `<ul class="answer-list">`
        val.answers.forEach(val => {
            answer += `<li>${val.post_content} (Skor: ${val.post_as})</li>`
        })
        answer += '</ul>'
        $('.table-soal > tbody').append(`<tr>
            <td>${no++}</td>
            <td>
                ${val.post_excerpt}
            </td>
            <td style="white-space:nowrap;">
                <div class="post-tag">
                    <i class="fa fa-tag"></i> ${categories}
                </div>
            </td>
            <td style="white-space:nowrap;">
                <a href="javascript:void(0)" onclick="toggleJawaban(${val.id},this)" class="act-btn" style="color:orange"><i class="fa fa-eye"></i> Lihat</a>
                <div class="jawaban-${val.id}" style='display:none'>
                <b>Jawaban</b>
                ${answer}
                </div>
            </td>
            <td style="white-space:nowrap;">
                <a href="javascript:void(0)" onclick="fetchJawaban(${val.id})" class="act-btn jawaban-btn" data-toggle="modal" data-target="#modalJawaban"><i class="fa fa-cog"></i> Manajemen Jawaban</a> |
                <a href="javascript:void(0)" onclick="fetchEditSoal(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deleteSoal(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function fetchEditSoal(id)
{
    $('#editQuestionForm').trigger('reset')
    let request = await fetch('<?= base_url() ?>/admin/question/find/'+id)
    let response = await request.json()
    $('#editQuestionForm').find('#id').val(response.id)
    $('#editQuestionForm').find('#title').val(response.post_title)
    CKEDITOR.instances.editor2.setData(response.post_content)
    // $('#editQuestionForm').find('#description').val(response.post_content)
    response.categories.forEach(category => {
        $('#editQuestionForm #category option').each(function(){
            if($(this).val() == category.category_id)
            {
                $(this).attr('selected','true')
                return
            }
        })
    })
}

async function fetchJawaban(id)
{
    $('#answerForm').find('#id').val(id)
    let request = await fetch('<?= base_url() ?>/admin/question/find-answer/'+id)
    let response = await request.json()

    $('.table-jawaban > tbody').html('')
    if(response.length == 0)
    {
        $('.table-jawaban > tbody').html('<tr><td colspan="3"><i>Tidak ada data!</i></td></tr>')
    }

    var no = 1

    response.forEach(val => {
        var statusJawaban = val.post_as == '0' ? '<span style="color:red"><i class="fa fa-close"></i> Skor 0</span>' : '<i class="fa fa-check"></i> Skor ' + val.post_as
        $('.table-jawaban > tbody').append(`<tr>
            <td>
                ${val.post_content}
                <a href="javascript:void(0)" class="act-btn jawaban-btn">${statusJawaban}</a> |
                <a href="javascript:void(0)" onclick="editJawaban(${val.id})" class="act-btn edit-btn"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deleteJawaban(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>`)
    })
}

async function deleteSoal(id)
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
            let request = await fetch('<?= route('admin/question/delete') ?>',{
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
                    'Soal berhasil dihapus.',
                    'success'
                )
                dataSoal = response.questions
                fetchToTable()
            }
        }
    })
}

async function editJawaban(id)
{
    let request = await fetch('<?= base_url() ?>/admin/question/answer/find/'+id)
    let response = await request.json()
    $('#answerForm').find('#id').val(response.post_parent_id)
    $('#answerForm').find('#jawaban_id').val(response.id)
    $('#answerForm').find('#skor').val(response.post_as)
    CKEDITOR.instances.editor3.setData(response.post_content)
}

async function updateJawaban(id)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan mengganti status jawaban ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then(async (result) => {
        if (result.value) {
            let request = await fetch('<?= route('admin/question/answer/update') ?>',{
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
                    'Success!',
                    'Status Jawaban berhasil diganti.',
                    'success'
                )
                await fetchJawaban($('#answerForm').find('#id').val())
                await loadData()
            }
        }
    })
}

async function deleteJawaban(id)
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
            let request = await fetch('<?= route('admin/question/answer/delete') ?>',{
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
                    'Jawaban berhasil dihapus.',
                    'success'
                )
                await fetchJawaban($('#answerForm').find('#id').val())
                await loadData()
            }
        }
    })
}

function filterSoal(keyword)
{
    var data = dataSoal.filter(soal => {
        var categories = soal.categories.map(e => e.category.category_name ).join(', ')
        return soal.post_title.includes(keyword) || soal.post_content.includes(keyword) || categories.includes(keyword)
    })

    fetchToTable(data)
}

function toggleJawaban(id,el)
{
    var status = document.querySelector('.jawaban-'+id).style.display
    if(status == 'none')
    {
        document.querySelector('.jawaban-'+id).style.display = 'block'
        el.innerHTML = '<i class="fa fa-eye"></i> Sembunyikan'
    }
    else
    {
        document.querySelector('.jawaban-'+id).style.display = 'none'
        el.innerHTML = '<i class="fa fa-eye"></i> Lihat'
    }
}

async function importSoal(el)
{
    Swal.fire({
        title: 'Konfirmasi ?',
        text: "Apakah anda yakin akan mengimport data soal ?",
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

            let request = await fetch('<?= route('admin/question/import') ?>',{
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
                    'Soal Berhasil di import.',
                    'success'
                )

                btnImport.innerHTML = "<i class='fa fa-upload'></i> Import Soal"
                loadData()
            }
        }
    })
}

window.onload = function(){
    loadData()
}
</script>