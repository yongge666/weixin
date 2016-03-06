<?php if (!defined('THINK_PATH')) exit();?><div class="scr_top">
    <img src="<?php echo ADDON_PUBLIC_PATH;?>/top.jpg"/>
    <div class="area">
        <img src="<?php echo ADDON_PUBLIC_PATH;?>/area.png"/>
        <div class="scratch_area">
        
          <?php if ($error) { ?>
            <div class="prize_text" style="font-size:16px; line-height:22px;"><?php echo ($error); ?></div>   
            <canvas style="display:none" /> 
          <?php } else { ?>
                <!-- 抽奖信息 -->
                <div class="prize_text" style="display:none"><?php echo ($prize["title"]); ?></div>
                <canvas />             
         <?php } ?> 
        </div>
    </div>
</div>
<?php if($prize['count'] > 0): ?><p class="repeat_tips"><?php if(!empty($data["max_num"])): ?>您还有<span id="leftCount"><?php echo ($prize["count"]); ?></span>次抽奖机会，<?php endif; ?><a href="javascript:window.location.reload();">再刮一次</a></p><?php endif; ?>
<div class="block_out">
    <div class="block_inner">
        <h6>活动说明</h6>
        <div class="desc"><?php echo ($data["intro"]); ?></div>
    </div>
</div>
<!--奖项 -->
<div class="block_out">
    <div class="block_inner">
        <h6>活动奖项</h6>
        <div class="desc">
        <?php if(empty($prizes)): ?><p class="empty">还没有设置奖项</p>
        <?php else: ?>
            <ul class="gift_list">
            <?php if(is_array($prizes)): $i = 0; $__LIST__ = $prizes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <p><?php echo ($vo["title"]); ?>:(<?php echo (intval($vo["num"])); ?>名)</p>
                    <?php echo (get_img_html($vo["img"])); ?>
                    <span><?php echo ($vo["name"]); ?></span>                   
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul><?php endif; ?>
        </div>
    </div>
</div>
    <!--中将记录 -->
    <div class="block_out">
        <div class="block_inner">
            <h6>我的中奖记录</h6>
            
            <?php if(empty($my_prizes)): ?><p class="empty">您目前还没有中过奖</p>
            <?php else: ?>
            <ul class="gift_history" id="my_gift_history">
            <?php if(!empty($prize["id"])): ?><li id="now_my_prize" style="display:none">
                        <span class="col_1">刚刚</span>
                        <span class="col_2"><?php echo ($prize["title"]); ?></span>
                    </li><?php endif; ?>                      
                <?php if(is_array($my_prizes)): $i = 0; $__LIST__ = $my_prizes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        <span class="col_1"><?php echo (time_format($vo["cTime"])); ?></span>
                        <span class="col_2"><?php echo ($vo["prize_title"]); ?></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
             </ul><?php endif; ?>
        </div>
    </div>
    <!--最新中将记录 -->
    <div class="block_out">
        <div class="block_inner">
            <h6>最新中奖记录</h6>
            <?php if(empty($new_prizes)): ?><p class="empty">暂还没有中奖记录</p>
            <?php else: ?>
            <ul class="gift_history">
              <?php if(is_array($new_prizes)): $i = 0; $__LIST__ = $new_prizes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <span class="col_1"><?php echo (time_format($vo["cTime"])); ?></span>
                    <span class="col_2"><?php echo (get_nickname($vo["uid"])); ?></span>
                    <span class="col_3"><?php echo ($vo["prize_title"]); ?></span>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
             </ul><?php endif; ?>
        </div>
    </div>
    <p class="copyright"><?php echo ($system_copy_right); echo ($tongji_code); ?></p>
    <script type="text/javascript">
    	 $.WeiPHP.initWxShare({
				title:'<?php echo ($data["title"]); ?>',
				imgUrl:'<?php echo (get_cover_url($data["cover"])); ?>',
				desc:'<?php echo ($data["intro"]); ?>',
				link:window.location.href
			})
    </script>
</div>