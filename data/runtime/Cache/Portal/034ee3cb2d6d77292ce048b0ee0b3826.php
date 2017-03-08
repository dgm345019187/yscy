<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo ($name); ?> <?php echo ($seo_title); ?> <?php echo ($site_name); ?></title>
        <meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
        <meta name="description" content="<?php echo ($seo_description); ?>">
    	<?php  function _sp_helloworld(){ echo "hello ThinkCMF!"; } function _sp_helloworld2(){ echo "hello ThinkCMF2!"; } function _sp_helloworld3(){ echo "hello ThinkCMF3!"; } ?>
	<?php $portal_index_lastnews="1,2"; $portal_hot_articles="1,2"; $portal_last_post="1,2"; $tmpl=sp_get_theme_path(); $default_home_slides=array( array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/1.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/2.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/3.jpg", "slide_url"=>"", ), ); ?>
	<meta name="author" content="ThinkCMF">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

   	<!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
	<link rel="icon" href="/yscy/themes/simplebootx/Public/assets/images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/yscy/themes/simplebootx/Public/assets/images/favicon.ico" type="image/x-icon">
    <link href="/yscy/themes/simplebootx/Public/assets/simpleboot/themes/simplebootx/theme.min.css" rel="stylesheet">
    <link href="/yscy/themes/simplebootx/Public/assets/simpleboot/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/yscy/themes/simplebootx/Public/assets/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="/yscy/themes/simplebootx/Public/assets/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
	<link href="/yscy/themes/simplebootx/Public/assets/css/style.css" rel="stylesheet">
	<style>
		/*html{filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(1);}*/
		#backtotop{position: fixed;bottom: 50px;right:20px;display: none;cursor: pointer;font-size: 50px;z-index: 9999;}
		#backtotop:hover{color:#333}
		#main-menu-user li.user{display: none}
	</style>
	
</head>
<body>
<?php echo hook('body_start');?>
<div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
     <div class="container">
       <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </a>
       <a class="brand" href="/yscy/"><img src="/yscy/themes/simplebootx/Public/assets/images/logo1.png"/></a>
       <div class="nav-collapse collapse" id="main-menu">
       	<?php
 $info=get_first_menu(); ?>
		<ul id="main-menu" class="nav">
                    <?php if(is_array($info)): foreach($info as $key=>$vo): if($vo["label"] == '首页' ): ?><li class="li-class" id="menu-item-5"><a href="<?php echo ($vo["href"]); ?>" target=""><?php echo ($vo["label"]); ?></a></li>
                             <?php else: ?>
                               <li class="li-class" id="menu-item-5"><a href="<?php echo ($vo["href"]); ?>&id=<?php echo ($vo["id"]); ?>" target=""><?php echo ($vo["label"]); ?></a></li><?php endif; endforeach; endif; ?>

                </ul>
		<ul class="nav pull-right" id="main-menu-user">
	
			<li class="dropdown user login">
	            <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
	            <img src="/yscy/themes/simplebootx/Public/assets/images/headicon.png" class="headicon"/>
	            <span class="user-nicename"></span><b class="caret"></b></a>
	            <ul class="dropdown-menu pull-right">
	               <li><a href="<?php echo U('user/center/index');?>"><i class="fa fa-user"></i> &nbsp;个人中心</a></li>
	               <li class="divider"></li>
	               <li><a href="<?php echo U('user/index/logout');?>"><i class="fa fa-sign-out"></i> &nbsp;退出</a></li>
	            </ul>
          	</li>
          	<li class="dropdown user offline">
	            <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
	           		<img src="/yscy/themes/simplebootx/Public/assets/images/headicon.png" class="headicon"/>登录<b class="caret"></b>
	            </a>
	            <ul class="dropdown-menu pull-right">
	               <li><a href="<?php echo U('api/oauth/login',array('type'=>'sina'));?>"><i class="fa fa-weibo"></i> &nbsp;微博登录</a></li>
	               <li><a href="<?php echo U('api/oauth/login',array('type'=>'qq'));?>"><i class="fa fa-qq"></i> &nbsp;QQ登录</a></li>
	               <li><a href="<?php echo leuu('user/login/index');?>"><i class="fa fa-sign-in"></i> &nbsp;登录</a></li>
	               <li class="divider"></li>
	               <li><a href="<?php echo leuu('user/register/index');?>"><i class="fa fa-user"></i> &nbsp;注册</a></li>
	            </ul>
          	</li>
		</ul>
		<div class="pull-right">
        	<form method="post" class="form-inline" action="<?php echo U('portal/search/index');?>" style="margin:18px 0;">
				 <input type="text" class="" placeholder="Search" name="keyword" value="<?php echo I('get.keyword');?>"/>
				 <input type="submit" class="btn btn-info" value="Go" style="margin:0"/>
			</form>
		</div>
       </div>
     </div>
   </div>
 </div>
<div class="container">
    <div>
        <?php if(is_array($pid)): foreach($pid as $key=>$vo): ?><div class="item" style='float:left;width:23%;margin: 10px;text-align:center'>              
                <div class="header">
                    <h3>
                        <?php if($flag == $vo['id']): ?><a href="<?php echo ($vo["href"]); ?>&pid=<?php echo ($id); ?>&flag=<?php echo ($vo["id"]); ?>"><font color="red"><?php echo ($vo["label"]); ?></font></a>
                            <?php else: ?>
                            <a href="<?php echo ($vo["href"]); ?>&pid=<?php echo ($id); ?>&flag=<?php echo ($vo["id"]); ?>"><?php echo ($vo["label"]); ?></a><?php endif; ?>
                    </h3>
                    <hr>
                </div>   
            </div><?php endforeach; endif; ?>
        <div class="item" style='float:left;width:23%;margin: 10px;text-align:center'>              
            <div class="header">
                <h3>

                    <?php if(empty($flag)): ?><a href="<?php echo U('yijian');?>&pid=<?php echo ($id); ?>"><font color="red">意见反馈</font></a>

                        <?php else: ?>
                        <a href="<?php echo U('yijian');?>&pid=<?php echo ($id); ?>">意见反馈</a><?php endif; ?>
                </h3>
                <hr>
            </div>   
        </div>
    </div>
<?php $lists = sp_sql_posts_paged("cid:$cat_id;order:post_date DESC;",20); ?>
    <div id="container" style='clear: both'>
        <div class="grid-sizer"></div>
        <?php if(is_array($infos)): $i = 0; $__LIST__ = $infos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><div class="item">
                <div class="tc-gridbox">
                    <div class="header">
                        <div class="item-image" >
                            <a href="<?php echo U('yijianxiangqing');?>&id=<?php echo ($vv["id"]); ?>">
                                <img src="http://192.168.1.22/yscy/data/upload/<?php echo ($vv["pic"]); ?>"
                                     class="img-responsive" alt="<?php echo ($vv["title"]); ?>" style='height:250px'>
                            </a>
                        </div>

                        <h3>
                            <a href="<?php echo U('yijianxiangqing');?>&id=<?php echo ($vv["id"]); ?>"><?php echo ($vv["title"]); ?></a>
                        </h3>
                        <hr>
                    </div>
                    <div class="body">
                        <a href="<?php echo U('yijianxiangqing');?>&id=<?php echo ($vv["id"]); ?>"><?php echo (msubstr($vv["content"],0,256)); ?></a>
                    </div>
                    <div class="footer">
                        <div class="pull-left">
                            <span class="meta"><?php echo (date('Y-m-d H:i:s',$vv["addtime"])); ?></span>
                        </div>
                        <div class="pull-right">
<!--                                                        <a href="javascript:;"><i class="fa fa-eye"></i><span><?php echo ($vo["post_hits"]); ?></span></a>
                                                        <a href="<?php echo U('article/do_like',array('id'=>$vo['object_id']));?>" class="js-count-btn"><i class="fa fa-thumbs-up"></i><span class="count"><?php echo ($vo["post_like"]); ?></span></a> 
                                                        <a href="<?php echo U('user/favorite/do_favorite',array('id'=>$vo['object_id']));?>" class="js-favorite-btn" data-title="<?php echo ($vo["post_title"]); ?>" data-url="<?php echo leuu('portal/article/index',array('id'=>$vo['object_id'],'cid'=>$vo['term_id']));?>" data-key="<?php echo sp_get_favorite_key('posts',$vo['object_id']);?>">
                                                            <i class="fa fa-star-o"></i>
                                                        </a>-->
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <!-- <div class="pagination"><ul><?php echo ($lists['page']); ?></ul></div> -->
    <div class="js-infinite-scroll-loading text-center" style="display: none;">正在加载...</div>
    <div id="nextpage"></div>
    <br>
<br>
<br>
<!-- Footer ================================================== -->
<hr>
<?php echo hook('footer');?>
<div id="footer">
	<div class="links">
		<?php $links=sp_getlinks(); ?>
		<?php if(is_array($links)): foreach($links as $key=>$vo): if(!empty($vo["link_image"])): ?><!-- <img src="<?php echo sp_get_image_url($vo['link_image']);?>"> --><!-- 如果想加个友链图片可以取消注释 --><?php endif; ?>
			<a href="<?php echo ($vo["link_url"]); ?>" target="<?php echo ($vo["link_target"]); ?>"><?php echo ($vo["link_name"]); ?></a><?php endforeach; endif; ?>
	</div>
	<p>
		Made by <a href="http://www.thinkcmf.com" target="_blank">ThinkCMF</a>
		Code licensed under the 
		<a href="http://www.apache.org/licenses/LICENSE-2.0" rel="nofollow" target="_blank">Apache License v2.0</a>.
		<br />
		Based on 
		<a href="http://getbootstrap.com/2.3.2/" target="_blank">Bootstrap</a>.
		Icons from 
		<a href="http://fortawesome.github.com/Font-Awesome/" target="_blank">Font Awesome</a>
	</p>
</div>
<div id="backtotop">
	<i class="fa fa-arrow-circle-up"></i>
</div>
<?php echo ($site_tongji); ?>

</div>

<!-- JavaScript -->
<script type="text/javascript">
//全局变量
var GV = {
    ROOT: "/yscy/",
    WEB_ROOT: "/yscy/",
    JS_ROOT: "public/js/"
};
</script>
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="/yscy/public/js/jquery.js"></script>
   <script src="/yscy/public/js/wind.js"></script>
   <script src="/yscy/themes/simplebootx/Public/assets/simpleboot/bootstrap/js/bootstrap.min.js"></script>
   <script src="/yscy/public/js/frontend.js"></script>
<script>
$(function(){
	$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });
	
	$("#main-menu li.dropdown").hover(function(){
		$(this).addClass("open");
	},function(){
		$(this).removeClass("open");
	});
	
	$.post("<?php echo U('user/index/is_login');?>",{},function(data){
          
		if(data.status==1){
			if(data.user.avatar){
				$("#main-menu-user .headicon").attr("src",data.user.avatar.indexOf("http")==0?data.user.avatar:"<?php echo sp_get_image_url('[AVATAR]','!avatar');?>".replace('[AVATAR]',data.user.avatar));
			}
			
			$("#main-menu-user .user-nicename").text(data.user.user_nicename!=""?data.user.user_nicename:data.user.user_login);
			$("#main-menu-user li.login").show();
			
		}
		if(data.status==0){
			$("#main-menu-user li.offline").show();
		}
		
		/* $.post("<?php echo U('user/notification/getLastNotifications');?>",{},function(data){
			$(".nav .notifactions .count").text(data.list.length);
		}); */
		
	});	
	;(function($){
		$.fn.totop=function(opt){
			var scrolling=false;
			return this.each(function(){
				var $this=$(this);
				$(window).scroll(function(){
					if(!scrolling){
						var sd=$(window).scrollTop();
						if(sd>100){
							$this.fadeIn();
						}else{
							$this.fadeOut();
						}
					}
				});
				
				$this.click(function(){
					scrolling=true;
					$('html, body').animate({
						scrollTop : 0
					}, 500,function(){
						scrolling=false;
						$this.fadeOut();
					});
				});
			});
		};
	})(jQuery); 
	
	$("#backtotop").totop();
	
	
});
</script>


<script src="/yscy/themes/simplebootx/Public/assets/js/imagesloaded.pkgd.min.js"></script>
<script src="/yscy/themes/simplebootx/Public/assets/js/masonry.pkgd.min.js"></script>
<script src="/yscy/themes/simplebootx/Public/assets/js/jquery.infiniteScroll.js"></script>
<script>

    $(function () {
        var $container = $('#container').masonry({
            columnWidth: '.grid-sizer',
            itemSelector: '.item'
        });

        $container.imagesLoaded().progress(function (imgLoad, image) {
            var msnry = $container.data('masonry');
            var itemSelector = msnry.options.itemSelector;
            var $item = $(image.img).parents(itemSelector);
            $('.tc-gridbox', $item).css('opacity', 1);
            msnry.layout();
        });

        $('#nextpage').infiniteScroll({
            loading: '.js-infinite-scroll-loading',
            total_pages: <?php echo ($lists['total_pages']); ?>,
            success: function (content) {
                var $items = $(content).find('#container .item');
                if ($items.length > 0) {
                    //$('.tc-gridbox',$items).css('opacity',1);
                    $container.append($items)
                            // add and layout newly prepended items
                            .masonry('appended', $items);
                    $items.imagesLoaded().progress(function (imgLoad, image) {
                        var msnry = $container.data('masonry');
                        var itemSelector = msnry.options.itemSelector;
                        var $item = $(image.img).parents(itemSelector);
                        $('.tc-gridbox', $item).css('opacity', 1);
                        msnry.layout();
                    });
                }
            },
            finish: function () {

            }
        });
    });




</script>
</body>
</html>