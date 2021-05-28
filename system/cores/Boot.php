<?php 

class Boot 
{

    function __construct()
    {
        session_start();
        require '../system/libraries/Functions.php';
        $app    = app();
        $pages  = pages();
        $routes = routes();

        // $URI = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
        $URI = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        $URI = trim($URI,'/');
        $URI = empty($URI) ? '/' : $URI;
        
        if($app['static_dir'])
            $URI = $URI == $app['static_dir'] ? '/' : $URI;

        if(isset($routes[$URI]))
        {
            history()->now($URI);
            $route = $routes[$URI];
            if(isset($route['middleware']))
            {
                $middleware = 'App\\Middlewares\\'.$route['middleware'];
                new $middleware;
            }

            $callback = $route['callback'];
            if(is_string($callback))
            {
                $explode_callback = explode('@',$callback);
                $classname = $explode_callback[0];
                $method = $explode_callback[1];

                $classname = 'App\\Controllers\\'.$classname;
                $callback = new $classname;
                if(method_exists($callback, $method))
                    $callback = $callback->{$method}();
                    // $callback = call_user_func(array($classname,$method));
                else
                    showError('Method '.$method.' in '.$classname.' doesn\'t exists');
            }
            else
                $callback = $callback(); // call_user_func($callback);

            $data = $callback;
            if(!$route['return'])
                return;

            if($route['return'] == 'json')
            {
                header('Content-Type: application/json');
                echo json_encode($data);
            }
            else
            {
                $explode_return = explode(':',$route['return']);
                if($explode_return[0] == 'page')
                {
                    $page = $pages[$explode_return[1]];
                    $filename = str_replace('.','/',$page['file']);
                }
                elseif($explode_return[0] == 'view')
                {
                    $filename = $explode_return[1];
                    $filename = str_replace('.','/',$filename);
                }
                elseif($explode_return[0] == 'redirect')
                {
                    $redirect_url = '';
                    if($explode_return[1] == 'page')
                        $redirect_url = $pages[$explode_return[2]]['url'];
                    elseif($explode_return[1] == 'route')
                        $redirect_url = $routes[$explode_return[2]];
                    elseif($explode_return[1] == 'url')
                        $redirect_url = $data;

                    redirect($redirect_url);
                    return;
                }
                elseif($explode_return[0] == 'partial')
                {
                    $page = isset($pages[$explode_return[1]]) ? $pages[$explode_return[1]] : false;
                    if($page)
                        $filename = str_replace('.','/',$page['file']);
                    else
                        $filename = str_replace('.','/',$explode_return[1]);
                    
                    return new TemplatePartial($data, $filename);
                    // if(isset($data))
                    //     extract($data);
                    // $file = '../template/'.$app['template_active'].'/'.$filename.'.php';
                    // if(!file_exists($file))
                    // {
                    //     $file = '../template/'.$app['template_active'].'/'.$filename.'.php';
                    // }
                    // require $file;
                    // return;
                }
                
                $file = '../template/'.$app['template_active'].'/'.$filename.'.php';
                $template = new Template($data, $file);
                
            }
        }
        else
        {
            $found = false;
            foreach($routes as $key => $value) {
                $url = str_replace("{","(?'",$key);
                $url = str_replace("}","'[^/]+)",$url);
                if ( preg_match( '~^'.$url.'$~i', $URI, $params ) ) {
                    $route = $routes[$key];
                    $found = true;
                    break;
                }
            }

            if($found)
            {
                if(isset($route['middleware']))
                {
                    $middleware = 'App\\Middlewares\\'.$route['middleware'];
                    new $middleware;
                }
                foreach($params as $key => $value)
                    if(is_int($key))
                        unset($params[$key]);

                history()->now($URI);
                $callback = $route['callback'];
                if(is_string($callback))
                {
                    $explode_callback = explode('@',$callback);
                    $classname = $explode_callback[0];
                    $method = $explode_callback[1];

                    $classname = 'App\\Controllers\\'.$classname;
                    $callback = new $classname;
                    if(method_exists($callback, $method))
                        $callback = call_user_func_array(array(new $classname, $method), $params);
                    else
                        showError('Method '.$method.' in '.$classname.' doesn\'t exists');
                }
                else
                    $callback = call_user_func_array($callback, $params);

                $data = $callback;
                if(!$route['return'])
                    return;

                if($route['return'] == 'json')
                {
                    header('Content-Type: application/json');
                    echo json_encode($data);
                }
                else
                {
                    $explode_return = explode(':',$route['return']);
                    if($explode_return[0] == 'page')
                    {
                        $page = $pages[$explode_return[1]];
                        $filename = str_replace('.','/',$page['file']);
                    }
                    elseif($explode_return[0] == 'view')
                    {
                        $filename = $explode_return[1];
                        $filename = str_replace('.','/',$filename);
                    }
                    elseif($explode_return[0] == 'redirect')
                    {
                        $redirect_url = '';
                        if($explode_return[1] == 'page')
                            $redirect_url = $pages[$explode_return[2]]['url'];
                        elseif($explode_return[1] == 'route')
                            $redirect_url = $routes[$explode_return[2]];
                        elseif($explode_return[1] == 'url')
                            $redirect_url = $data;

                        redirect($redirect_url);
                        return;
                    }
                    elseif($explode_return[0] == 'partial')
                    {
                        $page = isset($pages[$explode_return[1]]) ? $pages[$explode_return[1]] : '';
                        if($page)
                            $filename = str_replace('.','/',$page['file']);
                        else
                            $filename = str_replace('.','/',$explode_return[1]);
                        
                        if(isset($data))
                            extract($data);
                        $file = '../template/'.$app['template_active'].'/'.$filename.'.php';
                        require $file;
                        return;
                    }
                    
                    $file = '../template/'.$app['template_active'].'/'.$filename.'.php';
                    $template = new Template($data, $file);
                    
                }
            }
            else
                showError('Route '.$URI.' doesn\'t exists');
        }
    }
    
}