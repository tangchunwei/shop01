<?php
namespace models;

use PDO;

class BaseModel
{
    // 定义表名
    public $tableName = '';
    private static $_pdo = null;
    private $_dbname = 'basic_module';
    private $_host = '127.0.0.1';
    private $_user = 'root';
    private $_password = '520xiaona@520';
    // 初始化数据库连接
    public function __construct()
    {
        if (self::$_pdo === null) {
            // 生成pdo对象，连接数据库
            self::$_pdo = new PDO('mysql:dbname=' . $this->_dbname . ';host=' . $this->_host, $this->_user, $this->_password);
            // 设置编码
            self::$_pdo->exec('SET NAMES utf8');
        }
    }
    // 封装exec方法
    public function exec($sql)
    {
        $ret = self::$_pdo->exec($sql);
        if ($ret === false) {
            echo $sql, '<hr>';
            $error = self::$_pdo->errorInfo();
            die($error[2]);
        }
        return $ret;
    }
    // insert方法
    public function insert($data)
    {
        // 拼接sql语句
        $keys = array_keys($data);
        $values = array_values($data);
        // 拼接出新的字符串
        $keyString = implode(',', $keys);
        $valueString = implode(" ',' ", $values);
        // 拼接insert语句
        $sql = "insert into {$this->tableName} ($keyString) values('$valueString')";
        // 执行sql
        $this->exec($sql);
        // 返回新插入记录的id
        return self::$_pdo->lastInsertID();
    }
    // update 方法
    public function update($data, $where)
    {
        // 定义空数组
        $arr = [];
        // 取出数据
        foreach ($data as $k => $v) {
            $arr[] = " $k='$v' ";
        }
        // 把数组转成字符
        $sets = implode(',', $arr);
        // 拼接sql
        $sql = "update {$this->tableName} set $sets where  $where";
        $this->exec($sql);
    }
    //delete 方法
    public function delete($where)
    {
        $sql = "delete  from  {$this->tableName} where $where";
        return $this->exec($sql);
    }
    //查询 方法
    public function query($sql)
    {
        $ret = self::$_pdo->query($sql);
        if ($ret === false) {
            echo $sql, '<hr>';
            $error = self::$_pdo->errorInfo();
            die($error[2]);
            // echo "<pre>";
            // var_dump($error);
        }
        $ret->setFetchMode(PDO::FETCH_ASSOC);
        return $ret;
    }
    // 查询所有数据
    public function get($sql)
    {
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }
    // 从结果集中获取下一行
    public function getRow($sql)
    {
        $stmt = $this->query($sql);
        return $stmt->fetch();
    }
    // 从结果集中的下一行返回单独的一列。
    public function getOne($sql)
    {
        $stmt = $this->query($sql);
        return $stmt->fetchColumn();
    }
    // 统计条数
    public function count($where = 1)
    {
        $sql = "select count(*) from {$this->tableName} where $where";
        return $this->getOne($sql);
    }
    // 查询
    public function find($id, $select = '*')
    {
        $sql = "select $select from {$this->tableName} where id=$id";
        return $this->getRow($sql);
    }

}