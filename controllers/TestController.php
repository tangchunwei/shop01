<?php
namespace controllers;
use models\User;
class TestController{
    public function test(){
        // 实例化测试数据库
        $user=new User;
        $data=['name'=>'tangchunwei','age'=>18];
        // 调用插入方法
        // $user->insert($data);
        // 调用更新方法
        // $user->update($data,'id=1');
        // 调用删除方法
        // $num=$user->delete('id=2');
        // echo $num;
        // 调用查询方法
        $res=$user->find(4);
        echo "<pre>";
        var_dump($res);
    }
}