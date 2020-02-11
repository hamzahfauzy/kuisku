<?php

function app()
{
    $app    = require '../config/app.php';
    return $app;
}

function routes($URI = false)
{
    $routes = require '../config/routes.php';
    if($URI)
        return $routes[$URI];
    return $routes;
}

function pages($name = false)
{
    $pages  = require '../config/pages.php';
    if($name)
        return $pages[$name];
    return $pages;
}

function route($URI, $params = [])
{
    $routes = require '../config/routes.php';
    if(isset($routes[$URI]))
    {
        $url = $URI;
        if($url[0] == '/')
            $url = base_url().$URI;
        else
            $url = base_url().'/'.$URI;

        $param = '';
        if(count($params))
        {
            $param = '?';
            foreach($params as $k => $v)
                $param .= $k.'='.$v;
        }
        return $url.$param;
    }
    else
    {
        $found = false;
        foreach($routes as $key => $value) {
            $url = str_replace("{","(?'",$key);
            $url = str_replace("}","'[^/]+)",$url);
            if ( preg_match( '~^'.$url.'$~i', $URI, $param ) ) {
                $url = $URI;
                if($url[0] == '/')
                    $url = base_url().$URI;
                else
                    $url = base_url().'/'.$URI;
                return $url;
                break;
            }
        }
    }

    return false;
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function base_url()
{
    $app    = app();
    $application_protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $base_url = $application_protocol.$_SERVER['SERVER_NAME'];
    if($_SERVER['SERVER_PORT'])
        $base_url .= ':'.$_SERVER['SERVER_PORT'];
    
    if($app['static_dir'])
        $base_url .= '/'.$app['static_dir'];
        
    return $base_url;
}

function asset($path)
{
    return base_url().'/'.$path;
}

function old($key)
{
	return isset($_SESSION["old"][$key]) ? $_SESSION["old"][$key] : "";
}

function showError($message, $type = 404)
{
    $app    = app();
    $filename = '../template/'.$app['template_active'].'/errors/'.$type.'.php';
    if(file_exists($filename))
        require $filename;
    else
        echo "<h2>Error $type</h2><p>$message</p>";
    die();
}

function history()
{
    return new History;
}

function request()
{
    return new Request;
}

function session()
{
    return new Session;
}

function redirect($url)
{
    header('location:'.$url);
}


function strWordCut($string,$length,$end='....')
{
    $string = strip_tags($string);

    if (strlen($string) > $length) {

        // truncate string
        $stringCut = substr($string, 0, $length);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')).$end;
    }
    return $string;
}

function slug($string)
{
    $str = strWordCut($string,7,'');
    $str = strtolower($str);
    $str = str_replace(" ","-",$str);

    return $str;
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
