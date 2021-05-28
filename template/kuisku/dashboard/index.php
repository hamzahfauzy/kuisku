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
                    <h1 class="kategori"><?= $kategori ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Koleksi Soal</h5>
                    <h1 class="soal"><?= $soal ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Data Peserta</h5>
                    <h1 class="peserta"><?= $peserta ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Data Kuis</h5>
                    <h1 class="kuis"><?= $kuis ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary">
                    <h5 class="card-title">Total Data</h5>
                    <h1 class="total"><?= $total ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<script defer>
window.onload = function(event) { 
    var forEach = function (collection, callback, scope) {
        if (Object.prototype.toString.call(collection) === '[object Object]') {
            for (var prop in collection) {
                if (Object.prototype.hasOwnProperty.call(collection, prop)) {
                    callback.call(scope, collection[prop], prop, collection);
                }
            }
        } else {
            for (var i = 0, len = collection.length; i < len; i++) {
                callback.call(scope, collection[i], i, collection);
            }
        }
    };

    fetch('/statistic').then(res => res.json())
    .then(res => {
        forEach(res, (val,index) => {
            document.querySelector('.'+index).innerHTML = val
        })
    })
}
</script>