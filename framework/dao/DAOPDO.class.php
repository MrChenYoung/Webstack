<?php
namespace framework\dao;
use framework\dao\i_DAOPDO;
use framework\tools\StringTool;
use PDO;
use PDOException;

require_once "./framework/dao/i_DAOPDO.interface.php";
/**
 * Class DAOPDO PDO封装类
 * 封装PDO的操作
 */
class DAOPDO implements i_DAOPDO
{
    private static $instance;
    private $host;
    private $user;
    private $pass;
    private $port;
    private $charset;
    private $dbname;
    private $pdo;

    private function __construct(){}

    private function __clone(){}

    public static function getSingleton(){
        if(!self::$instance instanceof self){
            self::$instance = new self();
            self::$instance->loginDb($GLOBALS["db_info"]);
        }
        return self::$instance;
    }

    /**
     * 登录并连接到数据库
     */
    private function loginDb($option){
        $this -> host = isset($option['host'])?$option['host']:'';
        $this -> user = isset($option['user'])?$option['user']:'';
        $this -> pass = isset($option['pass'])?$option['pass']:'';
        $this -> dbname = isset($option['dbname'])?$option['dbname']:'';
        $this -> port = isset($option['port'])?$option['port']:3306;
        $this -> charset = isset($option['charset'])?$option['charset']:'utf8';

        // 登录mysql
        $dsn = "mysql:host=$this->host;port=$this->port";
        $res = $this->initDAO($dsn);
        if ($res["success"]){
            // 如果数据库不存在 创建
            $this->createDatabase();

            // 连接到account_db数据库
            $connectRes = $this->connectDb();
            if ($connectRes["success"]){
                // 登录成功
            }else {
                die("链接数据库失败,错误信息:".$connectRes["message"]);
            }
        }else {
            die("登录数据库失败,错误信息:".$res["message"]);
        }
    }

    /**
     * 链接数据库
     * @param $option
     */
    private function connectDb(){
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=$this->charset";
        return $this->initDAO($dsn);
    }

    //初始化PDO
    private function initDAO($dsn){
        try{
            $this ->pdo = new PDO($dsn,$this -> user,$this -> pass);
            return ["success"=>true,"message"=>"成功"];
        }catch (PDOException $e){
            return ["success"=>false,"message"=>$e->getMessage()];
        }
    }

    // 如果数据库不存在创建
    private function createDatabase(){
        $dbName = $this->dbname;
        $result = $this->pdo->exec("CREATE DATABASE IF NOT EXISTS {$dbName} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
        if (!$result) {
            die('数据库创建失败');
        }
    }

    // 创建表
    public function createTable($tableName,$sql){
        if (!$this->tableExist($tableName)){
            $this->fetchAll($sql);
        }
    }

    /**
     * 添加一条记录到表
     * @param $tbName               表名
     * @param $standardFiledName    判断标准字段，如果$data内该字段对应的value在表中不存在，则添加，否则无操作
     * @param array $data           要添加的一条记录键值对
     * @return bool                 添加是否成功
     */
    public function insertData($tbName,$standardFiledName,$data=[]){
        $success = false;
        if (!$data) return $success;

        // 查询表中是否有该记录
        $value = $data[$standardFiledName];
        $querySql = <<<EEE
SELECT * FROM $tbName WHERE $standardFiledName=$value
EEE;
        $res = $this -> FetchAll($querySql);

        if (!$res){
            // 没有该条数据 添加
            $fileds = [];
            $values = [];
            foreach ($data as $filed => $value){
                $fileds[] = $filed;
                $values[] = StringTool::singleQuotesInclude($value);
            }

            $fileStr = implode(",",$fileds);
            $valueStr = implode(",",$values);

            $insertSql = <<<EEE
                INSERT INTO $tbName ($fileStr) VALUES($valueStr);
EEE;
            // 添加数据
            $this -> FetchAll($insertSql);
            $success = true;
        }

        return $success;
    }

    // 判断数据表是否存在
    public function tableExist($tbName){
        // 判断表是否存在
        $sql1 = "SHOW TABLES LIKE '".$tbName."'";
        $res = $this -> fetchAll($sql1);

        if('1' == count($res)){
            // 表存在
            return true;
        }else {
            // 表不存在
            return false;
        }
    }

    /**
     * 删除表
     * @param $tableName
     */
    public function deleteTable($tableName){
        if ($this -> tableExist($tableName)){
            $sql = "DROP TABLE $tableName";
            $this -> fetchAll($sql);
        }
    }

    //查询一条记录的方法
    public function fetchOne($sql)
    {
       $pdo_statement = $this-> pdo -> query($sql);
        if($pdo_statement == false){
            //说明没有执行成功
            echo 'SQL语句:<br>'.$sql.'<br>'.'有误，详细信息如下:<br>'.$this->pdo->errorInfo()[2];
            exit;
        }
        //执行到这里说明没有错误
        return $pdo_statement -> fetch(PDO::FETCH_ASSOC);
    }
    //查询所有记录
    public function fetchAll($sql)
    {
        $pdo_statement = $this-> pdo -> query($sql);
        if($pdo_statement == false){
            //说明没有执行成功
            echo 'SQL语句:<br>'.$sql.'<br>'.'有误，详细信息如下:<br>'.$this->pdo->errorInfo()[2];
            exit;
        }
        //执行到这里说明没有错误
        return $pdo_statement -> fetchAll(PDO::FETCH_ASSOC);
    }

    //查询一个字段的值
    public function fetchColumn($sql)
    {
        $pdo_statement = $this-> pdo -> query($sql);
        if($pdo_statement == false){
            //说明没有执行成功
            echo 'SQL语句:<br>'.$sql.'<br>'.'有误，详细信息如下:<br>'.$this->pdo->errorInfo()[2];
            exit;
        }
        //执行到这里说明没有错误
        return $pdo_statement -> fetchColumn();
    }
    //执行增删改的方法
    public function query($sql)
    {
        $result = $this -> pdo -> exec($sql);
        //注意：可能返回等同于 false的非布尔值， 影响的记录数可能：0
        if($result === false){
            echo 'SQL语句:<br>'.$sql.'<br>'.'有误，详细信息如下:<br>'.$this -> pdo->errorInfo()[2];
            exit;
        }
        //执行到这里,sql没有错误，返回受影响的记录数
        return $result;
    }
    //返回刚刚执行插入语句的主键
    public function lastId()
    {
        return $this -> pdo -> lastInsertId();
    }
    //对数据引号转义、并包裹的方法
    public function quote($data)
    {
        return $this -> pdo -> quote($data);
    }
}