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
            <h2>Scoreboard Kuis (<?= $kuis->post_title ?>)</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="content-wrapper">
                <div class="table-panel">
                    <div class="panel-content">
                        <a href="<?= route('admin/kuis/view/'.$kuis->id) ?>" class="btn btn-warning"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
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
                            <th>No</th>
                            <th>Peserta</th>
                            <th>Skor</th>
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

<script async defer>
var dataPeserta   = {};

async function loadData()
{
    let request = await fetch('<?= route('admin/kuis/participant/'.$kuis->id) ?>')
    let response = await request.json()
    dataPeserta = response
    fetchToTable()
}

function fetchToTable(data = false)
{
    if(!data)
        data = dataPeserta

    data.sort((a,b) => {
        return b.skor - a.skor
    })
    $('.table-peserta > tbody').html('')
    if(data.length == 0)
    {
        $('.table-peserta > tbody').html('<tr><td colspan="3"><i>Tidak ada data!</i></td></tr>')
    }

    var no = 1;
    data.forEach(val => {
        var now = new Date(val.sesi.now)
        var waktu_selesai = new Date(val.sesi.waktu_selesai)
        var status = val.status == 1 ? '<span class="badge badge-warning">Sedang Mengerjakan</span>' : '<span class="badge badge-success">Selesai</span>'
        status = val.status == 1 && now > waktu_selesai ? '<span class="badge badge-success">Selesai</span>' : status
        $('.table-peserta > tbody').append(`<tr>
            <td width="10px">${no++}</td>
            <td>
                <b>${val.user.user_name}</b><br>
                ${status}
            </td>
            <td>${val.skor}</td>
        </tr>`)
    })
}

function filterPeserta(keyword)
{
    var data = dataPeserta.filter(peserta => {
        return peserta.user_name.includes(peserta) || peserta.user_email.includes(peserta)
    })

    fetchToTable(data)
}

loadData()
</script>