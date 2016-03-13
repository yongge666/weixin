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
	exit;
}
$weixin->getMsg();
$type = $weixin->msgtype;//消息类型
$eventType = $weixin->eventtype;//事件类型
$username = $weixin->msg['FromUserName'];//哪个用户给你发的消息,这个$username是微信加密之后的，但是每个用户都是一一对应的

if ($type=='event') {//微信用户第一次关注你的账号的时候，你的公众账号就会受到一条内容为'Hello2BizUser'的消息

	if($eventType=='subscribe'){
		////关注时回复
		$reply = $weixin->makeText('欢迎您关注天猫微店旗舰店，您将在第一时间获取最新打折促销活动哦（回复数字有惊喜哦）');
	}elseif($eventType=='unsubscribe'){
		//取消关注时回复
		$reply = $weixin->makeText('欢迎下次再来！');

	}


}elseif($type==='text') {
		//这里就是用户输入了文本信息
			$keyword = $weixin->msg['Content'];   //用户的文本消息内容
			//include_once("chaxun.php");//文本消息 调用查询程序
			//$chaxun= new chaxun(DEBUG,$keyword,$username);
			//$results['items'] =$chaxun->search();//查询的代码
	//关键词回复
	if($keyword==1){
		$reply = $weixin->makeText('今天在淘宝看一件衣服，有二个评论，其中一个中评一个好评。中评的内容是：和图片不一样，有色差，穿着不好看。好评的内容是：帮同学买的，他穿着很丑，我很满意。');
	}elseif($keyword==2){
		$reply = $weixin->makeText('长的既委婉又惊险。');
	}elseif($keyword==3){
		$reply = $weixin->makeText('认识你真好，不用去动物园了！');
	}elseif($keyword==4){
		$reply = $weixin->makeText('一哥们儿巨思念前女友，多年未见却很想复合。拨通电话后，那边慌忙挂下电话，说一会儿再打。过了两个小时，那边电话回过来了，前女友说：“不好意思，刚刚在生孩子…”哥们儿一口鲜血差点喷出来…');
	}elseif($keyword==5){
		$reply = $weixin->makeText('男人不能够太爱钱，否则成了吝惜鬼。男人又不能不爱钱，否则成了窝囊废。男人不能够话太少，否则变成了沉默寡言，愚木疙瘩。 男人又不能话太多，否则变成了心浮气躁，油嘴滑舌。男人不能说太多“我爱你”，否则变成了虚情假意，口是心非。 男人又不能不说“我爱你”，否则变成了毫无情趣，不解风情。');
	}elseif($keyword==6){
		$reply = $weixin->makeText('一和尚卖艺。有4个城管叫他走，他没理会接着耍。4个城管想砸和尚东西，但怕他功夫，只好打电话又叫来30多个城管，拿着棒子吓唬他：你走不走？和尚说我不走！有本事抓我！打架我不怕你人多！随后大叫一声，用手直接把砖头敲的粉碎！城管见了说：你要讲道理，出家人不要打打杀杀的。现场全笑喷了。');
	}elseif($keyword==7){
		$reply = $weixin->makeText('刘老师的长发和胡须留了十年了，一直没舍得剪掉，这天他终于下定决心换了一下形象，想试试看同学们还能不能认出他。他假装找人来到班里，问：“这班里的刘老师在不？”班长看到后，飞速地跑到校长室，气喘吁吁地说：“校长，不好了！我们刘老师剪了头发，连自己都认不出了！”');
	}elseif($keyword==8){
		$reply = $weixin->makeText('英雄，被美女废了；美女，被大款废了； 帅哥，被富婆废了；人生，被房贷废了； 青春，被工作废了；婚姻，被小三废了； 学生，被网游废了； 网游，被暴力废了；小孩，被三鹿废了；大人，被双汇废了； 信仰，被春哥废了；审美，被凤姐废了； 凤姐，被月月废了；梦想，被现实废了。');
	}else{

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