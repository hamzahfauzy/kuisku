<?php
date_default_timezone_set("Asia/Jakarta");
spl_autoload_register(function($classname){
    $class_map = require '../config/class_map.php';
    if(isset($class_map[$classname]))
    {
        $filename = '../'.$class_map[$classname].'.php';

        if(file_exists($filename))
        {
            require $filename;
            return;
        }
    }
    else
    {
        $explode_classname = explode('\\',$classname);
        if($explode_classname[0] == 'App')
            $classname = str_replace('App','app',$classname);
        $classname = str_replace('\\','/',$classname);
        $filename = '../'.$classname.'.php';
        if(file_exists($filename))
        {
            require $filename;
            return;
        }
    }
    die('404 File '.$filename.' Not Found');
});

$boot = new Boot;