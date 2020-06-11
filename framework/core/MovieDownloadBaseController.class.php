<?php


namespace framework\core;


use admin\controller\Base\MovieController;
use framework\tools\DatabaseDataManager;
use framework\tools\FFmpegTool;
use framework\tools\FileManager;
use framework\tools\HttpRequest;
use framework\tools\MovieDownload;
use framework\tools\StringTool;
use framework\tools\videoManager;

class MovieDownloadBaseController extends MovieController
{
    // 网络请求对象
    protected $request;
    // m3u8下载器
    protected $movieDownloader;

    public function __construct()
    {
        parent::__construct();
        // 创建网络请求对象
        $this -> request = new HttpRequest();

        // m3u8下载器
        $this -> movieDownloader = new MovieDownload();
    }

    /**
     * 获取视频相关数据
     */
    protected function loadMovieData(){}

    /**
     *  下载m3u8视频
     * @param $url 视频m3u8地址
     * @param $name 存放视频的名字
     */
    protected function downloadMovie($url,$name,$movieTitle){
        $this -> movieDownloader -> movieName = $name;
        $this -> movieDownloader -> saveDirPath = $this -> localMovieDirPath;
        $this -> movieDownloader -> movieTitle = $movieTitle;

        // 获取下载链接的后缀
        $extension = StringTool::getExtension($url);
        if (strtolower($extension) == ".mp4"){
            // MP4
            $this -> movieDownloader -> downloadMp4($url);
        }else {
            // m3u8
            $this -> movieDownloader -> downloadM3u8($url);
        }
    }

    /**
     * 下载视频封面缩略图
     * @param $url
     * @param $name
     */
    protected function downloadThumbnail($url,$name){
        // 如果缩略图文件夹不存在 创建
        if (!file_exists($this -> localThumbDirPath)){
            mkdir($this -> localThumbDirPath,0777,true);
        }
        $fullPath = $this -> localThumbDirPath."/".$name;
        // 如果不存在 下载
        if (!file_exists($fullPath)){
            $content = HttpRequest::getHttpsContent($url);
            file_put_contents($fullPath,$content);
        }
    }

    /**
     * 添加视频数据到数据库
     * @param $title   视频标题
     * @param $movieName 视频名字
     * @param $thumbnailName 视频缩略图名
     * @return 插入的视频id
     */
    protected function addMovieDataToDB($title,$movieName,$thumbnailName,$url,$collectionId){
        // 判断是不是mp4文件
        $isMp4 = false;
        if (strtolower(StringTool::getExtension($url)) == ".mp4"){
            $isMp4 = true;
        }

        $insertData = [
            "cover"                 =>      $thumbnailName,
            "movie_title"           =>      $title,
            "movie_name"            =>      $movieName,
            "click_count"           =>      0,
            "isMp4"                 =>      $isMp4 ? "1" : "0"
        ];
        DatabaseDataManager::getSingleton()->insert();
        $lastId = $this -> model -> insert($insertData);

        // 更新视频时长和视频大小字段
        $this -> updateMovieDuration($lastId,$collectionId,$movieName,$isMp4);
        $this -> updateMovieSize($lastId,$collectionId,$movieName,$thumbnailName,$isMp4);

        return $lastId;
    }

    /**
     * 跳转到数据库管理页面
     */
    protected function jumpToDbPage(){
        // 所有视频下载完成 跳转到数据库信息页面
        $dbName = $GLOBALS["config"]["dbname"];
        header("refresh:0;url=?m=admin&c=DBM&a=tableInfo&dbName=$dbName");
    }



}