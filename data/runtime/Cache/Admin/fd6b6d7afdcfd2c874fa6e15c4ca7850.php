<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>ThinkCMF <?php echo L('ADMIN_CENTER');?></title>
		<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta name="robots" content="noindex,nofollow">
		<link href="/yscy/admin/themes/simplebootx/Public/assets/css/admin_login.css" rel="stylesheet" />
		<style>
			#login_btn_wraper{
				text-align: center;
			}
			#login_btn_wraper .tips_success{
				color:#fff;
			}
			#login_btn_wraper .tips_error{
				color:#DFC05D;
			}
			#login_btn_wraper button:focus{outline:none;}
		</style>
		<script>
			if (window.parent !== window.self) {
					document.write = '';
					window.parent.location.href = window.self.location.href;
					setTimeout(function () {
							document.body.innerHTML = '';
					}, 0);
			}
		</script>
		
	</head>
<body>
	<div class="wrap">
		<h1><a>ThinkCMF</a></h1>
		<form method="post" name="login" action="<?php echo U('public/dologin');?>" autoComplete="off" class="js-ajax-form">
			<div class="login">
				<ul>
					<li>
						<input class="input" id="js-admin-name" name="username" type="text" placeholder="<?php echo L('USERNAME_OR_EMAIL');?>" title="<?php echo L('USERNAME_OR_EMAIL');?>" value="<?php echo cookie('admin_username');?>" data-rule-required="true"  data-msg-required=""/>
					</li>
					<li>
						<input class="input" id="admin_pwd" type="password" name="password" placeholder="<?php echo L('PASSWORD');?>" title="<?php echo L('PASSWORD');?>" data-rule-required="true"  data-msg-required=""/>
					</li>
					<li class="verifycode-wrapper">
						<?php echo sp_verifycode_img('length=4&font_size=20&width=248&height=42&use_noise=1&use_curve=0','style="cursor: pointer;" title="点击获取"');?>
					</li>
					<li>
						<input class="input" type="text" name="verify" placeholder="<?php echo L('ENTER_VERIFY_CODE');?>" />
					</li>
				</ul>
				<div id="login_btn_wraper">
					<button type="submit" name="submit" class="btn js-ajax-submit" data-loadingmsg="<?php echo L('LOADING');?>"><?php echo L('LOGIN');?></button>
                                       
				</div>
			</div>
		</form>
                 <button id='ss'>测试</button>
	</div>

<script>
var GV = {
    ROOT: "/yscy/",
    WEB_ROOT: "/yscy/",
    JS_ROOT: "public/js/"
};
</script>
<script src="/yscy/public/js/wind.js"></script>
<script src="/yscy/public/js/server.js"></script>
<script src="/yscy/public/js/md5.min.js"></script>
<script src="/yscy/public/js/jquery.js"></script>
<script type="text/javascript" src="/yscy/public/js/common.js"></script>
<script>
;(function(){
    $("#ss").click(function(){
          signData = [
                      {name: 'password', value: 123456},
                    {name: 'username', value: 13559209256},   
                ];
         var sign = getSign(signData, 'yscs')
       $.ajax({
   type: "POST",
   url: "http://localhost/yscy/api.php?ulogin",
   data: {username:"13559209256",password:123456,sign:sign},
   success: function(msg){
    
   }
});
    })
	document.getElementById('js-admin-name').focus();
})();
</script>
</body>
</html>