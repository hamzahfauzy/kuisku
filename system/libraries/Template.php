<?php

class Template
{
    public $title = "";
    public $application_name = "";
    public $header = "";
    public $content = "";
    public $navbar = "";
    public $sidebar = "";
    public $footer = "";
    public $app = "";
    public $visited = "";
    public $css = [];
    public $js = [];

    function __construct($data, $file)
    {
        $this->app    = app();
        $this->title  = $this->app['application_name'];
        $this->application_name  = $this->app['application_name'];

        if(!empty($data))
            extract($data);

        if(file_exists($file))
        {
            ob_start();
            require $file;
            $content = ob_get_clean();
        }
        else
        {
            $content = "";
        }

        require '../template/'.$this->app['template_active'].'/index.php';
    }

    static function partial($data, $file)
    {
        self::$app    = app();
        self::$title  = self::$app['application_name'];
        self::$application_name  = self::$app['application_name'];

        if(!empty($data))
            extract($data);

        require '../template/'.self::$app['template_active'].'/'.$file.'.php';
    }
}