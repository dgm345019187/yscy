<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
/**
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class PublicController extends AdminbaseController {

    public function _initialize() {
        C(S('sp_dynamic_config'));//加载动态配置
    }
    
    //后台登陆界面
    public function login() {
        $admin_id=session('ADMIN_ID');
    	if(!empty($admin_id)){//已经登录
    		redirect(U("admin/index/index"));
    	}else{
    	    $site_admin_url_password =C("SP_SITE_ADMIN_URL_PASSWORD");
    	    $upw=session("__SP_UPW__");
    		if(!empty($site_admin_url_password) && $upw!=$site_admin_url_password){
    			redirect(__ROOT__."/");
    		}else{
    		    session("__SP_ADMIN_LOGIN_PAGE_SHOWED_SUCCESS__",true);
    			$this->display(":login");
    		}
    	}
    }
    
    public function logout(){
    	session('ADMIN_ID',null); 
    	redirect(__ROOT__."/");
    }
    
    public function dologin(){
        $login_page_showed_success=session("__SP_ADMIN_LOGIN_PAGE_SHOWED_SUCCESS__");
        if(!$login_page_showed_success){
            $this->error('login error!');
        }
    	$name = I("post.username");
    	if(empty($name)){
    		$this->error(L('USERNAME_OR_EMAIL_EMPTY'));
    	}
    	$pass = I("post.password");
    	if(empty($pass)){
    		$this->error(L('PASSWORD_REQUIRED'));
    	}
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error(L('CAPTCHA_REQUIRED'));
    	}
    	//验证码
    	if(!sp_check_verify_code()){
    		$this->error(L('CAPTCHA_NOT_RIGHT'));
    	}else{
    		$user = D("Common/Member");
    		if(strpos($name,"@")>0){//邮箱登陆
    			$where['member_email']=$name;
    		}else{
    			$where['member_name']=$name;
    		}
    		
    		$result = $user->where($where)->find();
              
    		if(!empty($result) && $result['user_type']==1){
    			if(sp_compare_password($pass,$result['member_passwd'])){
    				
    				$role_user_model=M("RoleUser");
    				
    				$role_user_join = C('DB_PREFIX').'role as b on a.role_id =b.id';
    				
    				$groups=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$result["member_id"],"status"=>1))->getField("role_id",true);
                             
    				if( $result["member_id"]!=1 && ( empty($groups) || empty($result['member_state']) ) ){
    					$this->error(L('USE_DISABLED'));
    				}
    				//登入成功页面跳转
    				session('ADMIN_ID',$result["member_id"]);
    				session('name',$result["member_name"]);
    				$result['member_login_ip']=get_client_ip(0,true);
    				$result['member_login_time']=date("Y-m-d H:i:s");
    				$user->save($result);
    				cookie("admin_username",$name,3600*24*30);
    				$this->success(L('LOGIN_SUCCESS'),U("Index/index"));
    			}else{
    				$this->error(L('PASSWORD_NOT_RIGHT'));
    			}
    		}else{
    			$this->error(L('USERNAME_NOT_EXIST'));
    		}
    	}
    }

}