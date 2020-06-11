<?php

namespace framework\tools;

/**
 * Class MovieDownload 视频下载工具类
 */
class MovieDownload
{
    // 正在下载视频名(存放视频的目录)
    public $movieName;
    // 视频文件存储路径
    public $saveDirPath;
    // 正在下载视频的标题
    public $movieTitle;

    /**
     * @param $url m3u8视频链接
     * @param $saveDir 视频存储目录
     * @param $movieName 下载保存的视频名
     */
    public function downloadM3u8($url){
        // 创建存储视频文件的文件夹
        $saveDir = $this -> saveDirPath."/".$this->movieName;
        if (!file_exists($saveDir)){
            // 级联创建文件夹
            mkdir($saveDir,0777,true);
        }

        $m3u8Extension = trim(strrchr($url, '/'),'/');
        $tsFilePre = substr($url,0,strlen($url) - strlen($m3u8Extension));

        // 创建存放m3u8文件和ts文件的文件夹
        $movieFilePath = $this -> saveDirPath."/$this->movieName/";
        if (!file_exists($movieFilePath)){
            mkdir($movieFilePath);
        }

        // 获取m3u8内容(所有的ts碎片)
        LogManager::getSingleton() -> addLog("开始下载文件:".$this->movieTitle);
        LogManager::getSingleton() -> addLog("获取m3u8文件内容中....");

        // 下载m3u8文件
        $m3u8Content = HttpRequest::getHttpsContent($url);
        file_put_contents($movieFilePath."index.m3u8",$m3u8Content);

        // 获取ts片段数组
        preg_match_all('/.*\.ts/', $m3u8Content, $matches);
        if (empty($matches)){
            // m3u8地址错误
            LogManager::getSingleton() -> addLog("获取ts文件失败，无效的m3u8连接");
            return;
        }

        //下载所有的ts碎片
        $tsFiles = $matches[0];
        $tsCount = count($tsFiles);
        LogManager::getSingleton() -> addLog("获取ts文件成功，共".$tsCount."个ts文件");
        LogManager::getSingleton() -> addLog("开始下载ts文件...");
        foreach ($tsFiles as $k=>$v){
            LogManager::getSingleton() -> addLog("正在下载第".$k."/".$tsCount."个ts文件,文件名:".$v."...");
            $tsFileName = $movieFilePath.$v;
            $tsUrl = $tsFilePre.$v;
            LogManager::getSingleton()->addLog("下载链接:".$tsUrl);

            // 检测ts文件是否可以下载
            $canDownload = HttpRequest::getHttpsContent($tsUrl);
            if (!file_exists($tsFileName) && $canDownload){
                $startTime = time();
                try {
                    $tsContent = HttpRequest::getHttpsContent($tsUrl);
//                    $tsContent = $this->downloadTs($tsUrl,$tsFileName);
                    file_put_contents($tsFileName,$tsContent);
                }catch (Exception $e){
                    // 捕获异常
                    LogManager::getSingleton() -> addLog('第'.$k.'个ts文件下载失败');
                }

                $endTime = time();
                $size = filesize($tsFileName);
                $time = $endTime - $startTime;
                $size = $size / 1048576;
                $speed = 0;
                if ($time != 0){
                    $speed = $size / $time;
                }
                LogManager::getSingleton() -> addLog("当前下载速度（"."$speed"."）MB/s");
            }else {
                LogManager::getSingleton() -> addLog("第".$k."个ts文件已经存在，忽略下载");
            }
        }

        LogManager::getSingleton() -> addLog("所有ts文件已下载完毕");
    }

    private function downloadTs($url,$tsFileName){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 20971520);
        $flag=0;
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch ,$str) use (&$flag,$tsFileName){
            $len = strlen($str);
            $flag++;
            $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            if($flag==1){
                $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                header("HTTP/1.1 ".curl_getinfo($ch,CURLINFO_HTTP_CODE));
                header("Content-Type: ".$type);
                header("Content-Length: ".$size);
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control:max-age=2592000');
            }
//            echo $str;
            file_put_contents($tsFileName,$str,FILE_APPEND);
            return $len;
        });
        $output = curl_exec($ch);
    }

    /**
     * 下载mp4
     * @param $url MP4路径
     */
    public function downloadMp4($url){
        // 创建存储视频文件的文件夹
        $saveDir = $this -> saveDirPath."/".$this->movieName;
        if (!file_exists($saveDir)){
            // 级联创建文件夹
            mkdir($saveDir,0777,true);
        }

        // 保存mp4
        $mp4Path = $saveDir.'/movie.mp4';
        // 下载mp4
        $httpRequest = new HttpRequest();
        $httpRequest->url = $url;
        $httpRequest -> download($mp4Path,function ($progress){
            LogManager::getSingleton() -> addLog("已下载:".$progress."%");
        });
    }

}


