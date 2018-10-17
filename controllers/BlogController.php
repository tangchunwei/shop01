<?php
namespace controllers;
use models\Blog;
class BlogController{
    // 显示发表文章页面
    public function create(){
        return view("blogs.create");
    }
    public function store(){
        $blog=new Blog;
        echo "<pre>";
        var_dump($_POST['title']);
        $blog->insert([
            'title'=>$_POST['title'],
            'content'=>$_POST['content'],
            'is_show'=>$_POST['is_show'],
            'short_content'=>mb_substr($_POST['content'],0,255,'utf-8')
        ]);
        redirect('/');
    }
}