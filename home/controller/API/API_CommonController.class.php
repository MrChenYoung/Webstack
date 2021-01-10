<?php

namespace home\controller\API;

use admin\controller\API\API_AttachmentController;
use admin\controller\API\API_BaseController;
use framework\tools\DatabaseDataManager;

class API_CommonController extends API_BaseController
{
    // 获取前台账号列表
    public function loadHomeAccountLists(){
        echo "结果测试";

        
        // 分类id
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $catId = $_GET["id"];

        // 获取分类数据
        $catData = $this->loadCategory($catId);
        // 获取每个平台数据
        if (key_exists("platform_list",$catData)){
            $platIdLists = $catData["platform_list"];
            if (strlen($platIdLists) > 0){
                $platIdLists = explode(",",$platIdLists);
                $platLists = [];
                $platSortList = [];
                foreach ($platIdLists as $platId) {
                    $platData = $this->loadPlatform($platId);
                    // 获取账号列表
                    if (key_exists("web_list",$platData)){
                        $accIdLists = $platData["web_list"];
                        $accLists = [];
                        if (strlen($accIdLists) > 0){
                            $accIdLists = explode(",",$accIdLists);
                            $accSortList = [];
                            foreach ($accIdLists as $accId) {
                                $accData = $this->loadAccount($accId);
                                $accData["plat_name"] = $platData["plat_name"];
                                $accLists[] = $accData;
                                $accSortList[] = $accData["sort"];
                            }

                            array_multisort($accSortList, SORT_ASC, $accLists);
                        }
                        $platData["web_list"] = $accLists;
                    }
                    $platLists[] = $platData;
                    $platSortList[] = $platData["sort"];
                }

                array_multisort($platSortList, SORT_ASC, $platLists);
                $catData["platform_list"] = $platLists;
            }
        }

        echo $this->success($catData);
    }

    // 获取指定分类数据
    private function loadCategory($catId){
        $res = DatabaseDataManager::getSingleton()->find("category",["id"=>$catId]);
        if ($res && count($res) > 0){
            return $res[0];
        }else {
            return [];
        }
    }

    // 获取指定平台数据
    private function loadPlatform($platId){
        $res = DatabaseDataManager::getSingleton()->find("platform",["id"=>$platId]);
        if ($res && count($res) > 0){
            return $res[0];
        }else {
            return [];
        }
    }

    // 获取指定账号数据
    private function loadAccount($accId){
        $res = DatabaseDataManager::getSingleton()->find("web_list",["id"=>$accId]);
        if ($res && count($res) > 0){
            return $res[0];
        }else {
            return [];
        }
    }
}