<?php
/**
 * 工厂类，用来实例化模型的单例对象
 */
namespace framework\core;

class Factory
{
    public static function M($class)  //GoodsModel
    {
        static $model_list = [];

        //自动拼接上Model关键字
        //先判断一下类名是否包含Model
        if(substr($class,-5) != 'Model'){
            $class .= 'Model';
        }

        $modelPre = substr($class,0,3);
        if ($modelPre == "API"){
            $modelName = MODULE.'\\model\\'."API\\".$class;
        }else {
            $modelName = MODULE.'\\model\\'.$class;
        }

        //判断传递的类名是否含有命名空间: admin\model\Category
        if(strpos($class,'\\') !== FALSE){
            //说明找到了,说明有命名空间
            $modelName = $class;
        }

        $modelPath = APP.str_replace("\\","/",$modelName).".class.php";
        if (!file_exists($modelPath)){
            return null;
        }

        if(!isset($model_list[$modelName])){
            $model_list[$modelName] = new $modelName;
        }

        return $model_list[$modelName];
    }

    //生成伪静态URL地址
    //参数：MCA   home/question/add
    //参数2：额外参数 [page=>8]，生成第八页的伪静态地址

    //返回值： /0918/home/question/add
    public static function U($mca,$params=array())
    {
        //1. 使用$_SERVER['SCRIPT_NAME']
        //echo $_SERVER['SCRIPT_NAME'];   //  /0918/index.php
        $file = $_SERVER['SCRIPT_NAME'];
        $path = str_replace('index.php','',$file);

        $path .= $mca;      //      /0918/home/question/add

        if(!empty($params)){
            //说明传递了额外参数
            foreach ($params as $k=>$v){
                $path .= '/'.$k.'/'.$v;
            }
        }
        return $path;
    }
}