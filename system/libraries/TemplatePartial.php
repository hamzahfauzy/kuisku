<?php

class TemplatePartial
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

        require '../template/'.$this->app['template_active'].'/'.$file.'.php';
    }
}