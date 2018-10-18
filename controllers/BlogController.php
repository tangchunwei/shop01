<?php
namespace controllers;

use models\Blog;

class BlogController
{
    // 显示发表文章页面
    public function create()
    {
        return view("blogs.create");
    }
    // 保存文章到数据库
    public function store()
    {
        $blog = new Blog;
        echo "<pre>";
        var_dump($_POST['title']);
        $blog->insert([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'is_show' => $_POST['is_show'],
            'short_content' => mb_substr($_POST['content'], 0, 255, 'utf-8')
        ]);
        redirect('/blog/index');
    }
    // 显示文章列表
    public function index()
    {
        // 拼接搜索语句
        $where = 1;
        if (isset($_GET['keywords']) && $_GET['keywords']) {
            $where .= " and (title like '%{$_GET['keywords']}%' or content like '%{$_GET['keywords']}%')";
        }
        if (isset($_GET['start_date']) && $_GET['start_date']) {
            $where .= " and created_at >= '{$_GET['start_date']}'";
        }
        if (isset($_GET['updated_at']) && $_GET['updated_at']) {
            $where .= " and updated_at <= '{$_GET['updated_at']}' ";
        }
        if (isset($_GET['is_show']) && $_GET['is_show'] != '') {
            $where .= " and is_show ={$_GET['is_show']}";
        }
        // 拼接排序语句
        $orderBy = "created_at";
        $orderWay = 'asc';
        if (isset($_GET['order_by']) && $_GET['order_by'] == 'display') {
            $orderBy = 'display';
        }
        if (isset($_GET['order_way']) && $_GET['order_way'] == 'desc') {
            $orderWay = 'desc';
        }
        // echo $orderBy, $orderWay;
        // 拼接分页语句
        // 每页显示的条数
        $perpage = 10;
        // 获取当前页码
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        // 计算起始值
        $offset = ($page - 1) * $perpage;
        // 拼接limit
        $limit = $offset . ',' . $perpage;
        $blog = new Blog;
        // 制作分页按钮
        // 取出总条数
        $recordCount = $blog->count($where);
        // 计算总页数
        $pageCount = ceil($recordCount / $perpage);
        // 制作分页按钮
        $pageBtn = '';
        for ($i = 1; $i <= $pageCount; $i++) {
            $url = getUrlParams(['page']);
            // 为当前页码添加样式
            if ($i == $page) {
                $class = "class='page_active' ";
            } else {
                $class = '';
            }
            $pageBtn .= "<a {$class} href='?page={$i}{$url}'> {$i} </a>";
        }
        $blogs = $blog->get("select * from blogs where $where order by $orderBy $orderWay limit $limit");
        view("blogs.index", [
            'blogs' => $blogs,
            'pageBtn' => $pageBtn
        ]);
    }
    // 显示修改文章
    public function edit()
    {
        $id = $_GET['id'];
        $model = new Blog;
        $blog = $model->find($id);
        return view('blogs.edit', [
            'blog' => $blog
        ]);
    }
    // 修改文章
    public function update()
    {
        $id = $_GET['id'];
        $model = new Blog;
        $model->update([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'is_show' => $_POST['is_show'],
            'short_content' => mb_substr($_POST['content'], 0, 200, 'utf-8')
        ], "id={$id}");
        redirect("/blog/index");
    }
    // 删除文章
    public function delete()
    {
        $id=$_GET['id'];
        $model=new Blog;
        $model->delete("id={$id}");
        redirect('/blog/index');
    }
    // 显示日志详情
    public function detail(){
        $id=$_GET['id'];
        $model=new Blog;
        $blog=$model->find($id);
        $blog['display']++;
        // 更新浏览量
        $model->update([
            'display'=>$blog['display']
        ],"id={$id}");
        view('blogs.detail',[
            'blog'=>$blog
        ]);

    }
    // 生成随机汉字
    public function getChar($num)
    {
        $b = '';
        for ($i = 0; $i < $num; $i++) {
            $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
    // 模拟数据
    public function mock()
    {
        $blog = new Blog;
        for ($i = 0; $i < 100; $i++) {
            $blog->insert([
                'title' => $this->getChar(20),
                'content' => $this->getChar(1000),
                'display' => mt_rand(5, 1000),
                'is_show' => mt_rand(0, 1),
                'short_content' => $this->getChar(100)
            ]);
        }
    }
}