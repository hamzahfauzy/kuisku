<?php

return [

    '/' => [
        'middleware' => 'Auth',
        'callback' => 'HomeController@index',
        'return'   => 'page:index'
    ],

    'admin' => [
        'middleware' => 'Admin',
        'callback' => 'HomeController@index',
        'return'   => 'page:index'
    ],

    'admin/category' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@index',
        'return'   => 'page:kategori'
    ],

    'admin/category/get' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@index',
        'return'   => 'json'
    ],

    'admin/category/insert' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@insert',
        'return'   => 'json'
    ],

    'admin/category/update' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@update',
        'return'   => 'json'
    ],

    'admin/category/find/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@find',
        'return'   => 'json'
    ],

    'admin/category/delete' => [
        'middleware' => 'Admin',
        'callback' => 'CategoryController@delete',
        'return'   => 'json'
    ],

    'admin/question' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@index',
        'return'   => 'page:soal'
    ],

    'admin/question/get' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@index',
        'return'   => 'json'
    ],

    'admin/question/insert' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@insert',
        'return'   => 'json'
    ],

    'admin/question/answer/insert' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@insertAnswer',
        'return'   => 'json'
    ],

    'admin/question/update' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@update',
        'return'   => 'json'
    ],

    'admin/question/find/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@find',
        'return'   => 'json'
    ],

    'admin/question/find-answer/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@findAnswer',
        'return'   => 'json'
    ],

    'admin/question/delete' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@delete',
        'return'   => 'json'
    ],

    'admin/question/answer/update' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@updateAnswer',
        'return'   => 'json'
    ],

    'admin/question/answer/delete' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@deleteAnswer',
        'return'   => 'json'
    ],

    'admin/participant' => [
        'middleware' => 'Admin',
        'callback'   => 'ParticipantController@index',
        'return'     => 'page:peserta'
    ],

    'admin/participant/get' => [
        'middleware' => 'Admin',
        'callback' => 'ParticipantController@index',
        'return'   => 'json'
    ],

    'admin/participant/insert' => [
        'middleware' => 'Admin',
        'callback' => 'ParticipantController@insert',
        'return'   => 'json'
    ],

    'admin/participant/update' => [
        'middleware' => 'Admin',
        'callback' => 'ParticipantController@update',
        'return'   => 'json'
    ],

    'admin/participant/find/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'ParticipantController@find',
        'return'   => 'json'
    ],

    'admin/participant/delete' => [
        'middleware' => 'Admin',
        'callback' => 'ParticipantController@delete',
        'return'   => 'json'
    ],

    'admin/kuis' => [
        'middleware' => 'Admin',
        'callback'   => 'KuisController@index',
        'return'     => 'page:kuis'
    ],

    'admin/kuis/get' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@index',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/get/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@getSesi',
        'return'   => 'json'
    ],

    'admin/kuis/soal/get/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@getSoal',
        'return'   => 'json'
    ],

    'admin/kuis/insert' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@insert',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/insert/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@insertSesi',
        'return'   => 'json'
    ],

    'admin/kuis/update' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@update',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/update' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@updateSesi',
        'return'   => 'json'
    ],

    'admin/kuis/find/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@find',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/find/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@findSesi',
        'return'   => 'json'
    ],

    'admin/kuis/view/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@view',
        'return'   => 'view:admin.kuis.view'
    ],

    'admin/kuis/sesi/view/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@viewSesi',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/jadi-peserta' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@sesiJadiPeserta',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/batal-peserta' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@sesiBatalPeserta',
        'return'   => 'json'
    ],

    'admin/kuis/soal/tambah-soal' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@tambahSoal',
        'return'   => 'json'
    ],

    'admin/kuis/soal/hapus-soal' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@hapusSoal',
        'return'   => 'json'
    ],

    'admin/kuis/delete' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@delete',
        'return'   => 'json'
    ],

    'admin/kuis/sesi/delete' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@deleteSesi',
        'return'   => 'json'
    ],

    'participant' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@index',
        'return'     => 'partial:participant.index'
    ],

    'participant/exam' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@exam',
        'return'     => 'view:participant.exam'
    ],

    'participant/exam/answer' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@answer',
        'return'     => 'json'
    ],

    'participant/exam/finish' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@finish',
        'return'     => 'redirect:url'
    ],

    'login' => [
        'middleware' => 'Login',
        'callback' => 'AuthController@login',
        'return'   => 'partial:auth.login'
    ],

    'do-login' => [
        'callback' => 'AuthController@dologin',
        'return'   => 'redirect:url'
    ],

    'logout' => [
        'callback' => 'AuthController@logout',
        'return'   => 'redirect:page:index'
    ],

    'backend' => [
        'middleware' => 'Auth',
        'callback' => function(){
            echo "Backend";
        },
        'return'   => false
    ],

    '{slug}' => [
        'callback' => function($slug){
            echo "Slug ".$slug;
            return;
        },
        'return'   => false,
    ],

    'prefix' => [
        'group' => [
            '/' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
            'create' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
            'insert' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
            'edit' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
            'update' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
            'delete' => [
                'callback' => function(){

                },
                'return'   => false,
            ],
        ]
    ]

];