<?php
namespace User\Controller;

use Common\Controller\HomebaseController;

class LoginController extends HomebaseController {
	
    // 前台用户登录
	public function index(){
	    $redirect=I('get.redirect','');
	    if(empty($redirect)){
	        $redirect=$_SERVER['HTTP_REFERER'];
	    }else{
	        $redirect=base64_decode($redirect);
	    }
	    session('login_http_referer',$redirect);
	    
	    if(sp_is_user_login()){ //已经登录时直接跳到首页
	        redirect(__ROOT__."/");
	    }else{
	        $this->display(":login");
	    }
	}
	
	// 前台用户邮箱激活
	public function active(){
		$this->check_login();
		$this->display(":active");
	}
	
	// 前台用户邮箱激活提交
	public function doactive(){
		$this->check_login();
		$current_user=session('user');
		if($current_user['user_status']==2){
		    $this->_send_to_active();
		    $this->success('激活邮件发送成功，激活请重新登录！',U("user/index/logout"));
		}else if($current_user['user_status']==1){
		    $this->error('您的账号已经激活，无需再次激活！');
		}else{
		    $this->error('您的账号无法发送激活邮件！');
		}
	}
	
	// 前台用户忘记密码
	public function forgot_password(){
		$this->display(":forgot_password");
	}
	
	// 前台用户忘记密码提交(邮件方式找回)
	public function doforgot_password(){
		if(IS_POST){
			if(!sp_check_verify_code()){
				$this->error("验证码错误！");
			}else{
				$users_model=M("Member");
				$rules = array(
						//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
						array('email', 'require', '邮箱不能为空！', 1 ),
						array('email','email','邮箱格式不正确！',1), // 验证email字段格式是否正确
						
				);
				if($users_model->validate($rules)->create()===false){
					$this->error($users_model->getError());
				}else{
					$email=I("post.email");
					$find_user=$users_model->where(array("member_email"=>$email))->find();
					if($find_user){
						$this->_send_to_resetpass($find_user);
						$this->success("密码重置邮件发送成功！",__ROOT__."/");
					}else {
						$this->error("账号不存在！");
					}
					
				}
				
			}
			
		}
	}
	
	// 前台用户忘记密码提交(手机方式找回)
	public function do_mobile_forgot_password(){
	    if(IS_POST){
	    
    	    if(!sp_check_verify_code()){
    	        $this->error("验证码错误！");
    	    }
    	     
            $rules = array(
                //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
                array('mobile', 'require', '手机号不能为空！', 1 ),
                array('password','require','密码不能为空！',1),
                array('password','5,20',"密码长度至少5位，最多20位！",1,'length',3),
            );
            	
    	    $users_model=M("Member");
    	     
    	    if($users_model->validate($rules)->create()===false){
    	        $this->error($users_model->getError());
    	    }
    	    
    	    if(!sp_check_mobile_verify_code()){
    	        $this->error("手机验证码错误！");
            }
    	     
    	    $password=I('post.password');
    	    $mobile=I('post.mobile');
    	     
    	    $where['mobile']=$mobile;
    	     
    	    $users_model=M("Member");
    	    $result = $users_model->where($where)->count();
    	    if($result){
    	       $result=$users_model->where($where)->save(array('member_passwd' => sp_password($password)));
    	       if($result!==false){
    	           $this->success("密码重置成功！");
    	       }else{
    	           $this->error("密码重置失败！");
    	       }
    	    }else{
    	        $this->error('该手机号未注册！');
    	    }
    	}
	}
	
	/**
	 * 发送密码重置邮件
	 * @param array $user
	 */
	protected  function _send_to_resetpass($user){
		$options=get_site_options();
		//邮件标题
		$title = $options['site_name']."密码重置";
		$uid=$user['member_id'];
		$username=$user['member_name'];
	
		$activekey=md5($uid.time().uniqid());
		$users_model=M("Member");
                    
		$result=$users_model->where(array("member_id"=>$uid))->save(array("user_activation_key"=>$activekey));
		if(!$result){
			$this->error('密码重置激活码生成失败！');
		}
		//生成激活链接
		$url = U('user/login/password_reset',array("hash"=>$activekey), "", true);
		//邮件内容
		$template =<<<hello
		#username#，你好！<br>
		请点击或复制下面链接进行密码重置：<br>
		<a href="http://#link#">http://#link#</a>
hello;
		$content = str_replace(array('http://#link#','#username#'), array($url,$username),$template);
                 
		$send_result=sp_send_email($user['member_email'], $title, $content);
	
		if($send_result['error']){
			$this->error('密码重置邮件发送失败！');
		}
	}
	
	// 前台密码重置
	public function password_reset(){
	    $users_model=M("Member");
	    $hash=I("get.hash");
	    $find_user=$users_model->where(array("user_activation_key"=>$hash))->find();
	    if (empty($find_user)){
	        $this->error('重置码无效！',__ROOT__."/");
	    }else{
	        $this->display(":password_reset");
	    }
	}
	
	// 前台密码重置提交
	public function dopassword_reset(){
		if(IS_POST){
			if(!sp_check_verify_code()){
				$this->error("验证码错误！");
			}else{
				$users_model=M("Member");
				$rules = array(
						//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
						array('member_passwd', 'require', '密码不能为空！', 1 ),
						array('member_passwd','5,20',"密码长度至少5位，最多20位！",1,'length',3),
						array('repassword', 'require', '重复密码不能为空！', 1 ),
						array('repassword','password','确认密码不正确',0,'confirm'),
						array('hash', 'require', '重复密码激活码不能空！', 1 ),
				);
				if($users_model->validate($rules)->create()===false){
					$this->error($users_model->getError());
				}else{
					$password=sp_password(I("post.password"));
					$hash=I("post.hash");
					$result=$users_model->where(array("user_activation_key"=>$hash))->save(array("member_passwd"=>$password,"user_activation_key"=>""));
					if($result){
						$this->success("密码重置成功，请登录！",U("user/login/index"));
					}else {
						$this->error("密码重置失败，重置码无效！");
					}
					
				}
				
			}
		}
	}
	
	
    // 登录验证提交
    public function dologin(){

    	if(!sp_check_verify_code()){
    		$this->error("验证码错误！");
    	}
    	
    	$users_model=M("Member");
    	$rules = array(
    			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
    			array('username', 'require', '手机号/邮箱/用户名不能为空！', 1 ),
    			array('password','require','密码不能为空！',1),
    	
    	);
    	if($users_model->validate($rules)->create()===false){
    		$this->error($users_model->getError());
    	}
    	
    	$username=I('post.username');
    	
    	if(preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $username)){//手机号登录
    	    $this->_do_mobile_login();
    	}else{
    	    $this->_do_email_login(); // 用户名或者邮箱登录
    	}
    	 
    }
	
    // 处理前台用户手机登录
    private function _do_mobile_login(){
        $users_model=M('Member');
        $where = array("member_state"=>1);
        $where['mobile']=I('post.username');
        $password=I('post.password');
        $result = $users_model->where($where)->find();
        
        if(!empty($result)){
            if(sp_compare_password($password, $result['member_passwd'])){
                session('user',$result);
                //写入此次登录信息
                $data = array(
                    'member_login_time' => date("Y-m-d H:i:s"),
                    'member_login_ip' => get_client_ip(0,true),
                );
                $users_model->where(array('member_id'=>$result["member_id"]))->save($data);
                $session_login_http_referer=session('login_http_referer');
                $redirect=empty($session_login_http_referer)?__ROOT__."/":$session_login_http_referer;
                session('login_http_referer','');
        
                $this->success("登录验证成功！", $redirect);
            }else{
                $this->error("密码错误！");
            }
        }else{
            $this->error("用户名不存在或已被拉黑！");
        }
    }
    
    // 处理前台用户邮件或者用户登录
    private function _do_email_login(){

        $username=I('post.username');
        $password=I('post.password');
        $where = array("member_state"=>1);
        if(strpos($username,"@")>0){//邮箱登陆
            $where['member_email']=$username;
        }else{
            $where['member_name']=$username;
        }
        $users_model=M('member');
        $result = $users_model->where($where)->find();
        //print_r($result);die();
        $ucenter_syn=C("UCENTER_ENABLED");
        
        $ucenter_old_user_login=false;
         
        $ucenter_login_ok=false;
        if($ucenter_syn){
            cookie("thinkcmf_auth","");
            include UC_CLIENT_ROOT."client.php";
            list($uc_uid, $username, $password, $email)=uc_user_login($username, $password);
             
            if($uc_uid>0){
                if(!$result){
                    $data=array(
                        'member_name' => $username,
                        'member_email' => $email,
                        'member_passwd' => sp_password($password),
                        'member_login_ip' => get_client_ip(0,true),
                        'member_time' => time(),
                        'member_login_time' => time(),
                        'member_state' => '1',
                        'user_type'=>2,
                    );
                    $id= $users_model->add($data);
                    $data['member_id']=$id;
                    $result=$data;
                }
        
            }else{
                 
                switch ($uc_uid){
                    case "-1"://用户不存在，或者被删除
                        if($result){//本应用已经有这个用户
                            if(sp_compare_password($password, $result['member_passwd'])){//本应用已经有这个用户,且密码正确，同步用户
                                $uc_uid2=uc_user_register($username, $password, $result['member_email']);
                                if($uc_uid2<0){
                                    $uc_register_errors=array(
                                        "-1"=>"用户名不合法",
                                        "-2"=>"包含不允许注册的词语",
                                        "-3"=>"用户名已经存在",
                                        "-4"=>"Email格式有误",
                                        "-5"=>"Email不允许注册",
                                        "-6"=>"该Email已经被注册",
                                    );
                                    $this->error("同步用户失败--".$uc_register_errors[$uc_uid2]);
                                     
                                     
                                }
                                $uc_uid=$uc_uid2;
                            }else{
                                $this->error("密码错误！");
                            }
                        }
        
                        break;
                    case -2://密码错
                        if($result){//本应用已经有这个用户
                            if(sp_compare_password($password, $result['member_passwd'])){//本应用已经有这个用户,且密码正确，同步用户
                                $uc_user_edit_status=uc_user_edit($username,"",$password,"",1);
                                if($uc_user_edit_status<=0){
                                    $this->error("登陆错误！");
                                }
                                list($uc_uid2)=uc_get_user($username);
                                $uc_uid=$uc_uid2;
                                $ucenter_old_user_login=true;
                            }else{
                                $this->error("密码错误！");
                            }
                        }else{
                            $this->error("密码错误！");
                        }
                         
                        break;
                         
                }
            }
            $ucenter_login_ok=true;
            echo uc_user_synlogin($uc_uid);
        }
        //exit();
        if(!empty($result)){
            if(sp_compare_password($password, $result['member_passwd'])|| $ucenter_login_ok){
                session('user',$result);
                //写入此次登录信息
                $data = array(
                    'member_login_time' => date("Y-m-d H:i:s"),
                    'member_login_ip' => get_client_ip(0,true),
                );
                $users_model->where("member_id=".$result["member_id"])->save($data);

                $session_login_http_referer=session('login_http_referer');
                $redirect=empty($session_login_http_referer)?__ROOT__."/":$session_login_http_referer;
                session('login_http_referer','');
                $ucenter_old_user_login_msg="";
        
                if($ucenter_old_user_login){
                    //$ucenter_old_user_login_msg="老用户请在跳转后，再次登陆";
                }
        
                $this->success("登录验证成功！", $redirect);
            }else{
                $this->error("密码错误！");
            }
        }else{
            $this->error("用户名不存在或已被拉黑！");
        }
        
        
    }
}