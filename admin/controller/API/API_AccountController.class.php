<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;
use framework\tools\ImageTool;

class API_AccountController extends API_BaseController
{
    private $tableName;
    private $defaultLogo = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsSAAALEgHS3X78AAAC iElEQVQ4EaVTzU8TURCf2tJuS7tQtlRb6UKBIkQwkRRSEzkQgyEc6lkOKgcOph78Y+CgjXjDs2i4 4FXY9AMTlQRUELZapVlouy3d7kKtb0Zr0MSLTvL2zb75eL838xtTvV6H/xELBptMJojeXLCXyobn yog4YhzXYvmCFi6qVSfaeRdXdrfaU1areV5KykmX06rcvzumjY/1ggkR3Jh+bNf1mr8v1D5bLuvR 3qDgFbvbBJYIrE1mCIoCrKxsHuzK+Rzvsi29+6DEbTZz9unijEYI8ObBgXOzlcrx9OAlXyDYKUCz wwrDQx1wVDGg089Dt+gR3mxmhcUnaWeoxwMbm/vzDFzmDEKMMNhquRqduT1KwXiGt0vre6iSeAUH NDE0d26NBtAXY9BACQyjFusKuL2Ry+IPb/Y9ZglwuVscdHaknUChqLF/O4jn3V5dP4mhgRJgwSYm +gV0Oi3XrvYB30yvhGa7BS70eGFHPoTJyQHhMK+F0ZesRVVznvXw5Ixv7/C10moEo6OZXbWvlFAF 9FVZDOqEABUMRIkMd8GnLwVWg9/RkJF9sA4oDfYQAuzzjqzwvnaRUFxn/X2ZlmGLXAE7AL52B4xH gqAUqrC1nSNuoJkQtLkdqReszz/9aRvq90NOKdOS1nch8TpL555WDp49f3uAMXhACRjD5j4ykuCt f5PP7Fm1b0DIsl/VHGezzP1KwOiZQobFF9YyjSRYQETRENSlVzI8iK9mWlzckpSSCQHVALmN9Az1 euDho9Xo8vKGd2rqooA8yBcrwHgCqYR0kMkWci08t/R+W4ljDCanWTg9TJGwGNaNk3vYZ7VUdeKs YJGFNkfSzjXNrSX20s4/h6kB81/271ghG17l+rPTAAAAAElFTkSuQmCC";
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "web_list";
    }

    // 获取账号列表
    public function loadAccountList(){
        // 分类id
        $catId = "";
        if (isset($_GET["id"])){
            $catId = $_GET["id"];
        }

        // 查询网站列表
        $accData = DatabaseDataManager::getSingleton()->find($this->tableName,[],[],"ORDER BY sort");
        if ($accData !== false){
            // 获取每个账号所属的平台
            foreach ($accData as $key=>$accDatum) {
                $platId = $accDatum["plat_id"];
                $platData = DatabaseDataManager::getSingleton()->find("platform",["id"=>$platId],["plat_name"],"ORDER BY id");
                $platName = "";
                if ($platData !== false && count($platData) > 0){
                    $platName = $platData[0]["plat_name"];
                }
                $accData[$key]["plat_name"] = $platName;

                if ($accDatum["sort"] == 0){
                    DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$accDatum["id"]],["id"=>$accDatum["id"]]);
                }
            }

            echo $this->success($accData);
        }else {
            echo $this->failed("获取平台列表失败");
        }
    }

    // 请求指定账号数据
    public function loadAccount(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_GET["id"];

        $sql = <<<EEE
SELECT web_list.*,platform.plat_name FROM web_list,platform WHERE web_list.plat_id=platform.id AND web_list.id=$accId;
EEE;
        $res = DatabaseDataManager::getSingleton()->fetch($sql);

        if ($res !== false){
            echo $this->success($res[0]);
        }else {
            echo $this->failed("查询账号数据失败");
        }
    }

    // 添加账号
    public function addAccount(){
        // 所属平台
        if (!isset($_REQUEST["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_REQUEST["platId"];

        // 账户描述信息
        if (!isset($_REQUEST["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_REQUEST["desc"];

        // 用户名
        if (!isset($_REQUEST["user"])){
            echo $this->failed("需要user参数");
            die;
        }
        $user = $_REQUEST["user"];
        // base64解码
        $user = base64_decode($user);
        // 解码js进行的uri编码，为了处理中文
        $user = urldecode($user);

        // 登录地址
        if (!isset($_REQUEST["address"])){
            echo $this->failed("需要address参数");
            die;
        }
        $address = $_REQUEST["address"];
        // base64解码
        $address = base64_decode($address);

        // 网站logo
        if (!isset($_REQUEST["logo"])){
            echo $this->failed("需要logo参数");
            die;
        }
        $logo = $_REQUEST["logo"];
        // base64解码
        $logo = base64_decode($logo);
        // 解码js进行的uri编码，为了处理中文
        $logo = urldecode($logo);

        // 排序
        if (!isset($_REQUEST["sort"])){
            echo $this->failed("需要sort参数");
            die;
        }
        $sort = $_REQUEST["sort"];

        // 插入数据库
        $insertData = [
            "web_desc"      =>  $desc,
            "web_title"     =>  $user,
            "address"       =>  $address,
            "plat_id"       =>  $platId,
            "logo"          =>  $logo,
            "sort"          =>  $sort
        ];
        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,$insertData);
        if ($res){
            if ($sort == "0"){
                DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$res],["id"=>$res]);
            }

            // 添加id到平台表
            $resId = $this->addAccountToPlat($platId,$res);
            if ($resId){
                // 成功
                echo $this->success("网站添加成功 ");
                die;
            }
        }
        echo $this->failed("网站添加失败");
    }

    // 修改账号信息
    public function editAccount(){
        // id
        if (!isset($_REQUEST["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_REQUEST["id"];

        // 所属平台id
        if (!isset($_REQUEST["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_REQUEST["platId"];

        // 账户描述
        if (!isset($_REQUEST["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $accDesc = $_REQUEST["desc"];

        // 用户名
        if (!isset($_REQUEST["user"])){
            echo $this->failed("需要user参数");
            die;
        }
        $user = $_REQUEST["user"];
        // base64解码
        $user = base64_decode($user);
        // 解码js进行的uri编码，为了处理中文
        $user = urldecode($user);

        // 登录地址
        if (!isset($_REQUEST["address"])){
            echo $this->failed("需要address参数");
            die;
        }
        $address = $_REQUEST["address"];
        // base64解码
        $address = base64_decode($address);

        // 网站logo
        if (!isset($_REQUEST["logo"])){
            echo $this->failed("需要logo参数");
            die;
        }
        $logo = $_REQUEST["logo"];
        // base64解码
        $logo = base64_decode($logo);
        // 解码js进行的uri编码，为了处理中文
        $logo = urldecode($logo);

        // 排序
        if (!isset($_REQUEST["sort"])){
            echo $this->failed("需要sort参数");
            die;
        }
        $sort = $_REQUEST["sort"];
        $sortInt = (int)$sort;

        if ($sortInt > 0){
            $oldsort = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$accId]);
            $existSort = DatabaseDataManager::getSingleton()->find($this->tableName,["sort"=>$sort]);
            if ($existSort){
                $existSort = $existSort[0];
                $oldsort = $oldsort[0];
                DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$oldsort["sort"]],["id"=>$existSort["id"]]);
            }
        }

        // 查询原来所属的平台并移除
        $oldPlatData = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$accId],["plat_id"]);
        if ($oldPlatData){
            $oldPlatId = $oldPlatData[0]["plat_id"];
            $this->deleteAccFromPlatform($oldPlatId,$accId);
        }

        // 添加账号id记录到新的平台中
        $resId = $this->addAccountToPlat($platId,$accId);
        if ($resId){
            // 修改的数据
            $editData = [
                "plat_id"       =>  $platId,
                "web_desc"      =>  $accDesc,
                "web_title"     =>  $user,
                "address"       =>  $address,
                "logo"          =>  $logo,
                "sort"          =>  $sort
            ];
            $res = DatabaseDataManager::getSingleton()->update($this->tableName,$editData,["id"=>$accId]);
            if ($res){
                echo $this->success("修改成功");
                die;
            }
        }

        echo $this->failed("修改失败");
    }

    // 删除账号
    public function deleteAccount(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_GET["id"];

        // platid
        if (!isset($_GET["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_GET["platId"];

        // 先删除平台表中的账号列表记录
        $this->deleteAccFromPlatform($platId,$accId);

        // 再删除账号表中数据
        $res = DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$accId]);

        if ($res){
            echo $this->success("删除成功");
            die;
        }

        // 删除失败
        echo $this->failed("删除失败");
    }

    // 添加$accountId账户到$platId记录中
    private function addAccountToPlat($platId,$accountId){
        $platData = DatabaseDataManager::getSingleton()->find("platform",["id"=>$platId],["web_list"]);
        $acc_list = "";
        if ($platData) {
            $acc_list = $platData[0]["web_list"];
            if (strlen($acc_list) > 0) {
                // 以逗号分成数组
                $accArr = explode(",", $acc_list);
                $accArr[] = $accountId;
                $acc_list = implode(",", $accArr);
            } else {
                $acc_list = $accountId;
            }

            $resId = DatabaseDataManager::getSingleton()->update("platform", ["web_list" => $acc_list], ["id" => $platId]);
            return $resId;
        }

        return false;
    }

    // 从$platId平台中移除$deleteAccId账号记录
    private function deleteAccFromPlatform($platId,$deleteAccId){
        // 删除平台表中的账号列表记录
        $platData = DatabaseDataManager::getSingleton()->find("platform",["id"=>$platId],["web_list"]);
        if ($platData){
            $accStr = $platData[0]["web_list"];
            $accArr = explode(",",$accStr);

            $key = array_search($deleteAccId,$accArr);
            if($key !== false){
                unset($accArr[$key]);
                $accStr = implode(",",$accArr);
                $resId = DatabaseDataManager::getSingleton()->update("platform",["web_list"=>$accStr],["id"=>$platId]);
            }
        }
    }

    // 获取logo
    public function loadLogo(){
        // 网址
        if (!isset($_GET["address"])){
            echo $this->failed("需要address参数");
            die;
        }
        $address = $_GET["address"];
        $address = base64_decode($address);

        // id
        if (!isset($_GET["accId"])){
            echo $this->failed("需要accId参数");
            die;
        }
        $accId = $_GET["accId"];

        // 选择的接口
        if (!isset($_GET["selectApi"])){
            echo $this->failed("需要selectApi参数");
            die;
        }
        $selectApi = $_GET["selectApi"];
        $selectApi = strlen($selectApi) == 0 ? 1 : ((int)$selectApi);

        $logoData = "";
        switch ($selectApi){
            case 1:
                $logoData = $this->getLogo1($address);
                break;
            case 2:
                $logoData = $this->getLogo2($address);
                break;
            case 3:

                break;
        }

        if ($logoData){
            // 更新数据库
            if (strlen($accId) > 0){
                DatabaseDataManager::getSingleton()->update($this->tableName,["logo"=>$logoData],["id"=>$accId]);
            }
            echo $this->success($logoData);
        }else {
            // 使用默认的logo
            if (strlen($accId) > 0){
                DatabaseDataManager::getSingleton()->update($this->tableName,["logo"=>$this->defaultLogo],["id"=>$accId]);
            }
            echo $this->success("无法获取logo");
        }
    }

    // 取消添加账户，如果没有账户对应的记录，就删除刚添加的附件记录
    public function cacelAddAccount(){
        // id
        if (!isset($_GET["attId"])){
            echo $this->failed("需要attId参数");
            die;
        }
        $attId = $_GET["attId"];

        $res = (new API_AttachmentController())->clearAttachment("","",$attId);
    }

    // 上传logo
    public function uploadLogoImage(){
        // 图片base64b编码后
        if (!isset($_POST["base64ImgContent"])){
            echo $this->failed("需要base64ImgContent参数");
            die;
        }
        $base64ImgContent = $_POST["base64ImgContent"];

        // id
        if (!isset($_POST["webId"])){
            echo $this->failed("需要webId参数");
            die;
        }
        $webId = $_POST["webId"];

        if (strlen($webId) == 0){
            $inserRes = DatabaseDataManager::getSingleton()->insert($this->tableName,["logo"=>$base64ImgContent]);
            if ($inserRes){
                $webId = $inserRes;
            }
        }else {
            // 保存到数据库
            DatabaseDataManager::getSingleton()->update($this->tableName,["logo"=>$base64ImgContent],["id"=>$webId]);
        }

        echo $this->success($webId);
    }

    // 获取logo接口1
    private function getLogo1($address){
        $url = $address."/favicon.ico";
        $logoData = ImageTool::imgBase64Encode($url);
        return $logoData;
    }

    // 获取logo接口2
    private function getLogo2($address){
        $url ="http://tool.bitefu.net/ico/?url=".$address."&type=1";
        //1. 初始化curl请求
        $ch = curl_init();
        //2. 设置请求的服务器地址
        curl_setopt($ch,CURLOPT_URL,$url);
        //3. 不管get、post，都跳过证书验证
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = curl_exec($ch);
        //4. 关闭资源
        curl_close($ch);
        return $result;
    }

    // 获取账号网址的logo
    private function getLogo($address){
        $url = $address."/favicon.ico";
        $res = ImageTool::imgBase64Encode($url);

        if (!$res){
            // 没有获取到logo 使用默认的
            $res = $this->defaultLogo;
        }
        return $res;
    }
}