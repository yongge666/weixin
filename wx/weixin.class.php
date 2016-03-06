<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/6
 * Time: 14:41
 */
class Weixin
{
    public $token = '';//token
    public $debug =  false;//是否debug的状态标示，方便我们在调试的时候记录一些中间数据
    public $setFlag = false;
    public $msgtype = 'text';   //('text','image','location')
    public $msg = array();

    public function __construct($token,$debug)
    {
        $this->token = $token;
        $this->debug = $debug;
    }

    //获得用户发过来的消息（消息内容和消息类型  ）
    public function getMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if ($this->debug) {
            $this->write_log($postStr);
        }
        if (!empty($postStr)) {
            $this->msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->msgtype = strtolower($this->msg['MsgType']);
        }
    }

    //回复文本消息
    public function makeText($text='')
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        return sprintf($textTpl,$text,$FuncFlag);
    }

    //根据数组参数回复图文消息
    public function makeNews($newsData=array())
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $newTplHeader = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <ArticleCount>%s</ArticleCount><Articles>";
        $newTplItem = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $newTplFoot = "</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        $Content = '';
        $itemsCount = count($newsData['items']);
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;//微信公众平台图文回复的消息一次最多10条
        if ($itemsCount) {
            foreach ($newsData['items'] as $key => $item) {
                if ($key<=9) {
                    $Content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
                }
            }
        }
        $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
        $footer = sprintf($newTplFoot,$FuncFlag);
        return $header . $Content . $footer;
    }


    public function reply($data)
    {
        if ($this->debug) {
            $this->write_log('回复数据：'.$data);
        }
        echo $data;
    }


    public function valid()
    {
        if ($this->checkSignature()) {
            if( $_SERVER['REQUEST_METHOD']=='GET' )
            {
                echo $_GET['echostr'];
                exit;
            }
        }else{
            write_log('Token认证失败');
            exit;
        }
    }


    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


    //日志写入
    private function write_log($log){
        header("Content-type: text/html; charset=utf-8");
        /********************
        1、写入内容到文件,追加内容到文件
        2、打开并读取文件内容
        ********************/
         $file  = './logs/wx.log';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
         $content =date('Y-m-d H:i:s',time())."\n";
         $content .= $log."\n\n";
        // 这个函数支持版本(PHP 5)
        $f  = file_put_contents($file, $content,FILE_APPEND);

        /*if($data = file_get_contents($file)){; // 这个函数支持版本(PHP 4 >= 4.3.0, PHP 5)
            echo "写入文件的内容是：$data";
        }*/
    }



    public function search(){
        $record=array();  //定义返回结果的数组
        //普通的根据关键词查询数据库的操作  代码就不用分享了
        $list = $this->search($this->keyword);
        if(is_array($list)&&!empty($list)){
            foreach($list as $msg){
                $record[]=array(
                    //以下代码，将数据库中查询返回的数组格式化为微信返回消息能接收的数组形式，即title、description、picurl、url 详见微信官方的文档描述
                    'title' =>$msg['title'],
                    'description' =>$msg['discription'],
                    'picurl' => $msg['pic_url'],
                    'url' =>$msg['url']
                );
            }
        }
        return $record;
    }
}
?>