<?php


namespace framework\core;


use admin\controller\StoragePathController;
use framework\tools\DatabaseDataManager;
use framework\tools\DatabaseTableManager;
use framework\tools\StringTool;

class MovieBaseModel extends Model
{
    // 物理表名
    protected $logic_table = "list";

    // 根据视频标题自动分类
    public function movieClassify($title=""){
        // 查询所有分类
        $sql = <<<EEE
SELECT * FROM `movie_category`
EEE;
        $res = $this -> dao -> fetchAll($sql);
        $cats = [];
        foreach ($res as $v) {
            $keywords = explode(",",$v["keyword"]);
            $cat = ["keyword" => []];
            foreach ($keywords as $key) {
                if (!empty($key)){
                    $result = strpos($title,$key);

                    if ($result === false) continue;

                    if ($result == 0){
                        $cats["keyword"] = $key;
                        $cats["id"] = $v["id"];
                        $cats["catName"] = $v["cat_name"];
                        break 2;
                    }else {
                        if (!array_key_exists("pos",$cat)){
                            $cat["pos"] = $result;
                            array_push($cat["keyword"],$key);
                        }else {
                            if ($cat["pos"] >= $result){
                                $cat["pos"] = $result;
                                array_unshift($cat["keyword"],$key);
                            }else {
                                array_push($cat["keyword"],$key);
                            }
                        }
                    }

                    $cat["id"] = $v["id"];
                    $cat["catName"] = $v["cat_name"];
                }
            }

            $str = implode(",",$cat["keyword"]);
            $cat["keyword"] = $str;



            if (array_key_exists("pos",$cats)){
                if (array_key_exists("pos",$cat) && $cats["pos"] > $cat["pos"]){
                    $cats = $cat;
                }
            }else if (array_key_exists("pos",$cat)){
                $cats = $cat;
            }
        }

        return $cats;
    }

    /**
     *  根据视频标题查询数据库，检查视频是否已经存在
     * @param $movieTitle 视频标题
     * @return bool 视频是否已经存在
     */
    public function movieExist($movieTitle){
        $res = $this -> find(["movie_title" => $movieTitle]);
        if ($res){
            // 已经存在
            return true;
        }else {
            // 不存在
            return false;
        }
    }




    /**
     *  字节格式化成B KB MB GB TB
     * @param $size 字节大小
     * @return string 格式化后的结果
     */
    public function formatBytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2).$units[$i];
    }


    /**
     * @param $catId 根据分类id更新分类视频数量
     */
    public function updateMovieCategory($catId){
        if ($catId != 0) {
            // 根据分类id 修改分类表
            $where = "where `id`={$catId}";

            $sql = <<<EEE
UPDATE `movie_category` SET `movie_count` = `movie_count` + 1 $where
EEE;
            // 更新分类表
            $this -> dao -> query($sql);
        }
    }

    /**
     * 获取新的视频名或缩略图名
     * @param $oldName 旧名字
     * @return mixed  新名字
     */
    public function getNewName($oldName){
        // 获取后缀 .mp4 .jpg
        $suffix = strrchr($oldName, '.');

        $currentTime = uniqid('',false);
        $preName = substr($oldName,0,strlen($oldName) - strlen($suffix));
        $newName = str_replace($preName,$currentTime,$oldName);

        return $newName;
    }

    /**
     * 获取上次插入的一条数据的id
     * @return mixed
     */
    public function lastId(){
        return $this -> dao -> lastId();
    }

    /**
     * 统计视频数量
     * @param array $where 条件
     */
    public function movieCount($where=[]){
        return DatabaseDataManager::getSingleton() -> rowCount($this -> true_table,$where);
    }
}