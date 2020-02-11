<?php

class Connection extends \Mysqli
{

    public $table;

    function __construct()
    {
        $env = require '../environment.php';
        parent::__construct(
            $env['database_host'],
            $env['database_username'],
            $env['database_password'],
            $env['database_name']
        );
    }

}