<?php

return [

    '/' => [
        'middleware' => 'Auth',
        'callback' => 'HomeController@index',
        'return'   => 'page:index'
    ],

    'master' => [
        'middleware' => 'Master',
        'callback' => 'HomeController@index',
        'return'   => 'page:index'
    ],

    'master/category' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@index',
        'return'   => 'view:master.kategori.index'
    ],

    'master/category/get' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@index',
        'return'   => 'json'
    ],

    'master/category/insert' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@insert',
        'return'   => 'json'
    ],

    'master/category/update' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@update',
        'return'   => 'json'
    ],

    'master/category/find/{id}' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@find',
        'return'   => 'json'
    ],

    'master/category/delete' => [
        'middleware' => 'Master',
        'callback' => 'CategoryController@delete',
        'return'   => 'json'
    ],

    'master/customers' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@index',
        'return'   => 'view:master.customer.index'
    ],

    'master/customers/get' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@index',
        'return'   => 'json'
    ],

    'master/customers/insert' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@insert',
        'return'   => 'json'
    ],

    'master/customers/update' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@update',
        'return'   => 'json'
    ],

    'master/customers/find/{id}' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@find',
        'return'   => 'json'
    ],

    'master/customers/delete' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@delete',
        'return'   => 'json'
    ],

    'master/customers/add-user' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@addUser',
        'return'   => 'json'
    ],

    'master/customers/remove-user' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@removeUser',
        'return'   => 'json'
    ],

    'master/customers/{id}' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@users',
        'return'   => 'view:master.customer.users'
    ],

    'master/customers/{id}/get-users' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@users',
        'return'   => 'json'
    ],

    'master/customers/{id}/get-admin' => [
        'middleware' => 'Master',
        'callback' => 'Master\CustomerController@getAdmin',
        'return'   => 'json'
    ],

    'master/users' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@index',
        'return'   => 'view:master.user.index'
    ],

    'master/users/get' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@index',
        'return'   => 'json'
    ],

    'master/users/insert' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@insert',
        'return'   => 'json'
    ],

    'master/users/update' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@update',
        'return'   => 'json'
    ],

    'master/users/find/{id}' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@find',
        'return'   => 'json'
    ],

    'master/users/delete' => [
        'middleware' => 'Master',
        'callback' => 'Master\UserController@delete',
        'return'   => 'json'
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

    'admin/question' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@index',
        'return'   => 'page:soal'
    ],

    'admin/setting' => [
        'middleware' => 'Admin',
        'callback' => 'HomeController@setting',
        'return'   => 'page:pengaturan'
    ],

    'admin/setting/update' => [
        'middleware' => 'Admin',
        'callback' => 'HomeController@update',
        'return'   => 'redirect:url'
    ],

    'admin/setting/upload' => [
        'middleware' => 'Admin',
        'callback' => 'HomeController@upload',
        'return'   => 'json'
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

    'admin/question/image-upload' => [
        'middleware' => 'Admin',
        'callback' => 'SoalController@imageUpload',
        'return'   => 'false'
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

    'admin/kuis/participant/{id}' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@participant',
        'return'   => 'json'
    ],

    'admin/kuis/view/{id}/scoreboard' => [
        'middleware' => 'Admin',
        'callback' => 'KuisController@scoreboard',
        'return'   => 'view:admin.kuis.scoreboard'
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

    'participant/exam' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@exam',
        'return'     => 'view:participant.exam'
    ],

    'participant/exam/{no}' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@exam',
        'return'     => 'view:participant.exam'
    ],

    'participant/exam-partial' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@exam',
        'return'     => 'partial:participant.exam-partial'
    ],

    'participant/exam-partial/{no}' => [
        'middleware' => 'Participant',
        'callback'   => 'Participant\IndexController@exam',
        'return'     => 'partial:participant.exam-partial'
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