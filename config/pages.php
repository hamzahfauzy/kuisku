<?php

return [

    'index' => [
        'label' => '<i class="fa fa-home fa-fw"></i> Home',
        'url'   => route('/'),
        'file'  => 'dashboard.index'
    ],

    'kategori' => [
        'label' => '<i class="fa fa-list fa-fw"></i> Kategori',
        'url'   => route('admin/category'),
        'file'  => 'admin.kategori.index'
    ],

    'soal' => [
        'label' => '<i class="fa fa-cubes fa-fw"></i> Koleksi Soal',
        'url'   => route('admin/question'),
        'file'  => 'admin.soal.index'
    ],

    'peserta' => [
        'label' => '<i class="fa fa-users fa-fw"></i> Peserta',
        'url'   => route('admin/participant'),
        'file'  => 'admin.participant.index'
    ],

    'kuis' => [
        'label' => '<i class="fa fa-desktop fa-fw"></i> Kuis',
        'url'   => route('admin/kuis'),
        'file'  => 'admin.kuis.index'
    ],

    'pengaturan' => [
        'label' => '<i class="fa fa-cog fa-fw"></i> Pengaturan',
        'url'   => route('admin/setting'),
        'file'  => 'admin.setting.index'
    ]

];