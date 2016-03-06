<?php
/**
当配置好网址和token后，微信服务器会给所配置的网址发timestamp，nonce，根据这两个值和tocken该脚本计算出signature，如果计算出的签名和微信服务器发来的signature一致的话身份验证通过，则再将微信服务器发来的echostr返回给微信服务器

 */

//define your token
define("TOKEN", "c5cb0683dfd539596a682e4660ac336a");
$wechatObj = new wechatCallbackapiTest();

if(isset($_GET["echostr"])){
	$wechatObj->valid();
	exit;
}else{
	//身份验证只有一次，之后当用户发消息过来时,回复消息
	$wechatObj->responseMsg();
}

//$wechatObj->valid();



class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	/*$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;*/

					//关键字回复
					switch($keyword){
						case '1':
							$contentStr = "11111!";
							break;

						case '2':
							$contentStr = "222222!";
							break;

						case '3':
							$contentStr = "33333333!";
							break;


						case '4':
							$contentStr = "44444444!";
							break;
						//回复图片
						case 'image':
							$textTpl='<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Image>
								<MediaId><![CDATA[%s]]></MediaId>
								</Image>
								</xml>';
							$msgType = "image";
							$contentStr = '此处为图片的media_id';

						case 'img&text':
							$textTpl = '
								<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<ArticleCount>%s</ArticleCount>
								<Articles>
								<item>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								<PicUrl><![CDATA[%s]]></PicUrl>
								<Url><![CDATA[%s]]></Url>
								</item>
								<item>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								<PicUrl><![CDATA[%s]]></PicUrl>
								<Url><![CDATA[%s]]></Url>
								</item>
								</Articles>
								</xml>';
							$msgType = "news";
							//图文条数
							$articleCount = 1;
							$title='标题';
							$description = '描述';
							//图片链接支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
							$picurl1 = 'http://www.so.com/link?url=http%3A%2F%2Fimage.so.com%2Fv%3Fq%3D%25E5%259B%25BE%25E7%2589%2587%26src%3D360pic_strong%26fromurl%3Dhttp%253A%252F%252Fwww.bbzhi.com%252Ffengjingbizhi%252Fsijizhutibizhiyiqingliangxiari%252Fdown_17800_2.htm%23multiple%3D1%26dataindex%3D2%26id%3D98da722a3a3f4aff5743e32cad389a79%26itemindex%3D0&q=%E5%9B%BE%E7%89%87&ts=1457194324&t=739779c3685affbd623ab67adbb1078';
							$picurl2 = 'http://www.so.com/link?url=http%3A%2F%2Fimage.so.com%2Fv%3Fq%3D%25E5%259B%25BE%25E7%2589%2587%26src%3D360pic_strong%26fromurl%3Dhttp%253A%252F%252Fwww.bbzhi.com%252Ffengjingbizhi%252Fsijizhutibizhiyiqingliangxiari%252Fdown_17800_2.htm%23multiple%3D1%26dataindex%3D2%26id%3D98da722a3a3f4aff5743e32cad389a79%26itemindex%3D0&q=%E5%9B%BE%E7%89%87&ts=1457194324&t=739779c3685affbd623ab67adbb1078';
							$url1 = '';
							$url2 = '';
							$contentStr = '此处为图片的media_id';

						default:
							$contentStr = '请输入数字';
							break;
					}

					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;


                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>