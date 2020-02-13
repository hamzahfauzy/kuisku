<?php 
$this->title .= " | Dashboard"; 
$this->visited = "index";
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h2>Dashboard</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Data Kategori</h5>
                    <h1><?= $kategori ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Koleksi Soal</h5>
                    <h1><?= $soal ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Data Peserta</h5>
                    <h1><?= $peserta ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Data Kuis</h5>
                    <h1><?= $kuis ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Total Data</h5>
                    <h1><?= $total ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>