<?php
/**
 * 基础模型类
 */
namespace framework\core;
use framework\dao\DAOPDO;

class Model
{
    protected $dao;
    protected $true_table;  //真实表名
    protected $pk;          //主键字段名

    //初始化真实的数据表名
    public function __construct()
    {
        //初始化dao
        $this -> initDAO();

        //初始化真实表名
        $this -> initTrueTable();

        //初始化表的结构（主键）
        $this -> initFields();
    }
    //初始化表的结构
    private function initFields()
    {
        // 判断表是否存在
        if($this -> dao -> tableExist($this -> true_table) == false){
            return;
        }

        $sql = "DESC $this->true_table";

        $result = $this -> dao -> fetchAll($sql);
        foreach ($result as $v){
            if($v['Key'] == 'PRI'){
                $this -> pk = $v['Field'];
            }
        }
    }
    //初始化真实表名
    private function initTrueTable()
    {
        //如果用户设置了logic_table属性，采用下面的方法
        if(isset($this->logic_table)){
            $tbPre = $GLOBALS['config']['table_prefix'];
            if ($tbPre){
                $this -> true_table = '`'.$tbPre."_".$this->logic_table.'`';
            }else {
                $this -> true_table = '`'.$this->logic_table.'`';
            }
            return;
        }
        //如果用户没有设置logic_table属性，我们根据类名解析出对应的数据表名
        $class = get_class($this);      //获得某个对象的类名:
        //die($class);                  //home\model\GoodsModel
        $modelName = basename(str_replace("\\","/",$class));  //GoodsModel
        //去掉Model关键字，因为表名没有Model
        $modelName = substr($modelName,0,-5); //Goods |  ProductImage

        //$modelName = 'ProductImage';
        //如果表名是多个单词的合成，例如：ProductImage，对应的表名就应该是：product_image
        $rule = '/(?<=[a-z])([A-Z])/';
        $modelName = preg_replace($rule,'_$1',$modelName);
        $modelName = strtolower($modelName);

        $tablePre = $GLOBALS['config']['table_prefix'];
        if ($tablePre){
            $this -> true_table = '`'.$tablePre."_".$modelName.'`';
        }else {
            $this -> true_table = '`'.$modelName.'`';
        }

    }
    //初始化dao
    private function initDAO()
    {
        $option = $GLOBALS['dbInfo'];
        $this -> dao = DAOPDO::getSingleton($option);
    }
    /**
     * 自动查询，封装执行查询的sql语句
     * SELECT `goods_id`,`goods_name` FROM tn_goods WHERE goods_id = 1
     * 参数1：查询的条件，$where = ['goods_id'=>1]
     * 参数2：查询的字段，$fields = ['goods_id','goods_name','shop_price']
     */
    public function find($where = [],$fields=[],$other="")
    {
        //1. 确定查询的条件
        if(empty($where)){
            $where_str = '';
        }else{
            $key = array_keys($where)[0];
            $value = array_values($where)[0];

            $where_str = " WHERE `$key` = '$value'";
        }
        //2. 确定查询的字段
        if(empty($fields)){
            $fields_str = '*';
        }else{
            $field = [];
            foreach ($fields as $k=>$v){
                $field[] = "`$v`";
            }
            //将数组的值使用,连接成字符串, `goods_id`,`goods_name`,`shop_price`
            $fields_str = implode(',',$field);
        }
        //3. 拼接sql语句
        $sql = "SELECT $fields_str FROM $this->true_table $where_str $other";

        //4. 执行sql语句，返回结果
        return $this -> dao -> fetchAll($sql);
    }

    /**
     * 删除数据，根据主键字段删除
     * 参数：主键值，删除主键等于谁的记录
     */
    public function delete($pk_value)
    {
        $sql = "DELETE FROM $this->true_table WHERE `$this->pk` = $pk_value";
        return $this -> dao -> query($sql);
    }

    /**
     * 添加数据
     * 参数，向哪个字段插入什么值，$data = ['goods_name'=>'小米Mix2','shop_price'=>2000]
     * 目标：INSERT INTO `tn_goods`(`goods_name`,`shop_price`) VALUES('小米Mix2',2000)
     */
    public function insert($data)
    {
       $sql = "INSERT INTO $this->true_table";

       //2. 将传递的数组中的字段名拼接成字符串
        $keys = [];
        $values = [];
       foreach($data as $k=>$v){
           $keys[] = "`$k`";                    // `goods_name`    `shop_price`
           $values[] = $this->dao->quote($v);   // '小米Mix2'  '1 or 1=1 \''
       }
       //3. 拼接字段部分
       $fields = '('.implode(',',$keys).')';    // (`goods_name`,`shop_price`)
       $sql .= $fields;

       //3. 拼接字段值部分
       $fileds = ' VALUES('.implode(',',$values).')';
       $sql .= $fileds;

       //执行sql语句
       $this -> dao -> query($sql);
       //返回刚刚插入的数据的主键值
       return $this -> dao -> lastId();
    }

    /**
     * 自动更新的操作
     * 参数1：更新的字段，$data = ['goods_name'=>'小米6','shop_price'=>2300]
     * 参数2：更新有条件：$where = ['goods_id' => 506]
     * 目标：UPDATE tn_goods SET `goods_name`='小米6',`shop_price`=2300 WHERE `goods_id`=506
     */
    public function update($data,$where)
    {
        //1. 判断是否传递了where条件
        if(empty($where)){
            return false;
        }else{
            //拼接WHERE 条件，例如：WHERE `goods_id`=506
            foreach($where as $k=>$v){
                //将可能存在的 单引号 转义并包裹
                $v = $this->dao->quote($v);
                $where_str = " WHERE `$k` = $v";
            }
        }

        //2. 拼接更新的字段
        $arr = [];
        foreach($data as $k=>$v){
            $v = $this->dao->quote($v);
            $arr[] = "`$k` = $v";
        }
        $fields = implode(',',$arr);

        //3. 拼接SQL语句
        $sql = "UPDATE $this->true_table SET $fields $where_str";

        return $this -> dao -> query($sql);
    }
}