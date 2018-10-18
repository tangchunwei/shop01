<?php
namespace controllers;
use models\User;
class TestController{
    public function test(){
        for($i=1;$i<10;$i++){
            echo $i."<br>";
        }
        echo "循环结束";
    }
}