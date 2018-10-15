<?php
// 实现类的自动加载
function load($class){
    $path=str_replace('\\','/',$class);
    require_once('../'.$path.'.php');
    echo $class;
}
spl_autoload_register("load");
$blog=new controllers\IndexController;