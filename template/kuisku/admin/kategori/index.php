<?php 
$this->title .= " | Kategori"; 
$this->visited = "kategori";

// $this->css = [
//     asset('css/wordpress-admin.css')
// ];

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

<script defer>
var dataKategori = {};
async function loadData()
{
    let request = await fetch('<?= route('admin/category/get') ?>')
    let response = await request.json()
    dataKategori = response
    fetchToTable()
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
            </td>
        </tr>`)
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