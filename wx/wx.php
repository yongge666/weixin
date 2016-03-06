<?php
/**
当配置好网址和token后，微信服务器会给所配置的网址发timestamp，nonce，根据这两个值和tocken该脚本计算出signature，如果计算出的签名和微信服务器发来的signature一致的话身份验证通过，则再将微信服务器发来的echostr返回给微信服务器

 */

//define your token
define("TOKEN", "c5cb0683dfd539596a682e4660ac336a");
define('DEBUG', true);
include('weixin.class.php');//引用刚定义的微信消息处理类
$weixin = new Weixin(TOKEN,DEBUG);//实例化
if(isset($_GET["echostr"])){
	$weixin->valid();
	$weixin->write_log('token校验失败!');
	exit;
}
$weixin->getMsg();
$type = $weixin->msgtype;//消息类型
$username = $weixin->msg['FromUserName'];//哪个用户给你发的消息,这个$username是微信加密之后的，但是每个用户都是一一对应的
if ($type==='text') {
	if ($weixin->msg['Content']=='Hello2BizUser') {//微信用户第一次关注你的账号的时候，你的公众账号就会受到一条内容为'Hello2BizUser'的消息
			$reply = $weixin->makeText('欢迎你关注天猫微店旗舰店，您将在第一时间获取最新打折促销活动哦');
		}else{
		//这里就是用户输入了文本信息
			$keyword = $weixin->msg['Content'];   //用户的文本消息内容
			//include_once("chaxun.php");//文本消息 调用查询程序
			//$chaxun= new chaxun(DEBUG,$keyword,$username);
			//$results['items'] =$chaxun->search();//查询的代码

			//模拟查询数据
			$results['content'] ='消息内容';
			$results['items'][0]['title'] = '标题1';//
			$results['items'][1]['title'] = '标题2';//
			$results['items'][0]['description'] = 'description1';//
			$results['items'][1]['description'] = 'description2';//
			$results['items'][0]['picurl'] = 'http://licaiguanjia.wang/blog/wp-content/themes/enigma/images/1.png';//
			$results['items'][1]['picurl'] = 'http://licaiguanjia.wang/blog/wp-content/themes/enigma/images/2.png';//
			$results['items'][0]['url'] = 'http://www.sunupedu.com/';//
			$results['items'][1]['url'] = 'http://licaiguanjia.wang/blog/';//
			$reply = $weixin->makeNews($results);
	}
}elseif ($type==='location') {
	//用户发送的是位置信息  稍后的文章中会处理
}elseif ($type==='image') {
	//用户发送的是图片 稍后的文章中会处理
}elseif ($type==='voice') {
	//用户发送的是声音 稍后的文章中会处理
}
$weixin->reply($reply);

?>

?>