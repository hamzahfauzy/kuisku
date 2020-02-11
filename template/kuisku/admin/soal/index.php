<?php 
$this->title .= " | Soal"; 
$this->visited = "soal";
// $this->css = [
//     asset('css/wordpress-admin.css')
// ];

$this->js = [
    asset('js/sweetalert2@9.js'),
    asset('js/sweetalert2.min.js'),
    asset('js/ckeditor.js'),
];
?>
<link rel="stylesheet" href="<?= asset('css/wordpress-admin.css') ?>">
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
                    </div>
                    <div class="panel-content not-grow">
                        <div class="form-inline">
                            <label for="">Cari &nbsp;</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Kata Kunci.." onkeyup="filterSoal(this.value)">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-soal">
                    <thead>
                        <tr>
                            <th width="20px">#</th>
                            <th width="40%">Judul</th>
                            <th>Ringkasan</th>
                        </tr>
                    </thead>
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
        <h5 class="modal-title" id="exampleModalLabel">Tambah Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="addQuestionForm" action="<?= route('admin/question/insert') ?>">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" rows="10" id="description" class="form-control editor"></textarea>
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
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control editor2"></textarea>
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
<div class="modal fade" id="modalJawaban" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control editor3"></textarea>
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

ClassicEditor
.create( document.querySelector( '.editor' ), {
	// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
} )
.then( editor => {
	window.editor = editor;
} )
.catch( err => {
	console.error( err.stack );
} );

ClassicEditor
.create( document.querySelector( '.editor2' ), {
	// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
} )
.then( editor => {
	window.editor2 = editor;
} )
.catch( err => {
	console.error( err.stack );
} );

ClassicEditor
.create( document.querySelector( '.editor3' ), {
	// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
} )
.then( editor => {
	window.editor3 = editor;
} )
.catch( err => {
	console.error( err.stack );
} );

async function loadData()
{
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
        post_content:window.editor.getData(),
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
        window.editor.setData('')
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
        post_content:window.editor2.getData(),
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
        window.editor2.setData('')
        dataSoal = response.questions
        fetchToTable()
    }

}

async function saveAnswer()
{
    var data = {
        post_parent_id:$('#answerForm').find('#id').val(),
        // post_content:$('#answerForm').find('#description').val(),
        post_content:window.editor3.getData(),
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
        // $('#answerForm').find('#description').val("")
        window.editor3.setData('')
        await fetchJawaban($('#answerForm').find('#id').val())
    }

}

function fetchToTable(data = false)
{
    if(!data)
        data = dataSoal
    $('.table-soal > tbody').html('')
    if(data.length == 0)
    {
        $('.table-soal > tbody').html('<tr><td colspan="3"><i>Tidak ada data!</i></td></tr>')
    }

    var no = 1;
    data.forEach(val => {
        var categories = val.categories.map(e => e.category.category_name ).join(', ')
        $('.table-soal > tbody').append(`<tr>
            <td>${no++}</td>
            <td>
                <b>${val.post_title}</b>
                <br>
                <a href="javascript:void(0)" onclick="fetchJawaban(${val.id})" class="act-btn jawaban-btn" data-toggle="modal" data-target="#modalJawaban"><i class="fa fa-eye"></i> Jawaban</a> |
                <a href="javascript:void(0)" onclick="fetchEditSoal(${val.id})" class="act-btn edit-btn" data-toggle="modal" data-target="#modalEdit"><i class="fa fa-pencil"></i> Edit</a> |
                <a href="javascript:void(0)" onclick="deleteSoal(${val.id})" class="act-btn delete-btn"><i class="fa fa-trash"></i> Hapus</a>
            </td>
            <td>
                ${val.post_excerpt}<br>
                <div class="post-tag">
                    <i class="fa fa-tag"></i> ${categories}
                </div>
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
    window.editor2.setData(response.post_content)
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
        var statusJawaban = val.post_as == 'Jawaban Salah' ? '<span style="color:red"><i class="fa fa-close"></i> Jawaban Salah</span>' : '<i class="fa fa-check"></i> Jawaban Benar'
        $('.table-jawaban > tbody').append(`<tr>
            <td>
                ${val.post_content}
                <a href="javascript:void(0)" onclick="updateJawaban(${val.id})" class="act-btn jawaban-btn">${statusJawaban}</a> |
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
            }
        }
    })
}

function filterSoal(keyword)
{
    var data = dataSoal.filter(soal => {
        return soal.post_title.includes(keyword) || soal.post_content.includes(keyword)
    })

    fetchToTable(data)
}

loadData()
</script>