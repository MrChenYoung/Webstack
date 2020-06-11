<?php


namespace framework\tools;


class LogManager
{
    // 单利对象
    private static $instance;
    // 运行日志文件路径
    private $logFilePath;
    // 错误入职文件路径
    private $errLogFilePath;

    private function __construct()
    {
        // 创建日志文件
        $logDir = ROOT."Logs";
        if (!file_exists($logDir)){
            mkdir($logDir);
        }
        $this -> logFilePath = $logDir."/log.txt";
        $this-> errLogFilePath = $logDir."/errLog.txt";
        if (!file_exists($this -> logFilePath)){
            $this -> clearLog();
        }
        if (!file_exists($this->errLogFilePath)){
            $this->clearErrorLog();
        }
    }

    private function __clone()
    {
    }

    public static function getSingleton(){
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 添加运行日志
     * @param $content 日志内容
     */
    public function addLog($content,$errLog=false){
        // 日志文件路径
        $logPath = $errLog ? $this->errLogFilePath : $this->logFilePath;

        // 获取当前时间
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s', time());
        $content = "[$time]".$content."\r\n";
        $res = file_put_contents($logPath,$content,FILE_APPEND);
    }

    /**
     * 清空运行log文件
     */
    public function clearLog(){
        file_put_contents($this -> logFilePath,"");
    }

    /**
     * 添加错误日志
     * @param $content
     */
    public function addErrorLog($content){
        $this->addErrorLog($content,true);
    }

    /**
     * 清空错误日志
     */
    public function clearErrorLog(){
        file_put_contents($this->errLogFilePath,"");
    }

    /**
     * 获取日志文件的md5值 用于监测日志文件是否发生改变
     * @return false|string
     */
    public function getLogFileMd5(){
        return md5_file($this -> logFilePath);
    }

    /**
     * 获取日志文件内容
     * @return false|string
     */
    public function getLogContents(){
        $logUrl = "http://".$_SERVER['HTTP_HOST']."/LoveMovie/Logs/log.txt";

        // 请求Log信息
        $request = new HttpRequest();
        $request -> url = $logUrl;
        $returnCode = $request -> reachable();

        if ($returnCode == 200){
            // 文件存在
            $logContent = file_get_contents($logUrl);
            $logContent = str_replace("\r\n","<br>",$logContent);
            return $logContent;
        }else {
            // 文件不存在
            return "log文件不存在";
        }
    }
}