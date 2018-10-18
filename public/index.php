<?php
// 定义根目录常量
define('ROOT', dirname(__FILE__) . '/../');
// 实现类的自动加载
function load($class)
{
    $path = ROOT . str_replace('\\', '/', $class);
    require_once($path . '.php');
}
spl_autoload_register("load");
// 实现view视图显示函数
function view($file, $data = [])
{
    // 如果传了数据，就把数组展开成变量
    if ($data) {
        extract($data);
    }
    // 加载试图 
    require ROOT . 'views/' . str_replace('.', '/', $file) . '.html';
}
// 解析路由，以及任务分发
function route()
{   
    //获取 URL 
    $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    // 定义默认的控制器以及方法
    $defautController = 'Indexcontroller';
    $defaultAction = 'index';
    if ($url == '/') {
        return [
            $defautController,
            $defaultAction,
        ];
    }
    // 判断是否是根路径
    else if (strpos($url, '/', 1) !== false) {
        // 去掉第一个/
        $url = ltrim($url, '/');
        // 把url转成数组
        $route = explode('/', $url);
        // 格式化控制器
        $route[0] = ucfirst($route[0]) . 'Controller';
        return $route;

    } else {
        die("请求的路径不合法");
    }
}
// 跳转方法
function redirect($url)
{
    header("location:" . $url);
    exit();
}
// 获取url参数
function getUrlParams($except = [])
{
    $ret = '';
    foreach ($_GET as $k => $v) {
        if(!in_array($k,$except)){
            $ret .= "&$k=$v";
        }
    }
    return $ret;
}
$route = route();
$controller = "controllers\\$route[0]";
$action = $route[1];
$_C = new $controller;
$_C->$action();