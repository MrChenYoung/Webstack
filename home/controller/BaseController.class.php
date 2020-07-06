<?php

namespace home\controller;

use admin\controller\API\API_CategoryController;
use framework\core\Controller;

class BaseController extends Controller
{
    protected $catList;
    public function __construct()
    {
        parent::__construct();

        // 获取分类数据
        $catData = $this->getCategoryList();
        $this->dataExtension = ["catList"=>$catData];
    }

    // 获取分类
    public function getCategoryList(){
        $catController = new API_CategoryController();
        $catList = $catController->loadCategotyList(true);
        $this->catList = $catList;

        $data = [];
        foreach ($catList as $cat) {
            $catId = $cat["id"];
            $domId = "cat_".$catId;
            $catName = $cat["cat_title"];
            $catIcon = $cat["cat_icon"];
            $href = "?m=home&c=Account&a=index&catId=";
            $href .= $catId;
            $dom = <<<EEE
                    <li>
                        <a id=$domId class='iconfont icon-$catIcon' href=$href>
                            <span>$catName</span>
                        </a>
                    </li>
EEE;
            $data[] = $dom;
        }

        return $data;
    }
}