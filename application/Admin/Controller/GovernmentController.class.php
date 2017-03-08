<?php
/**
 * 后台首页
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class GovernmentController extends AdminbaseController {
	
	public function _initialize() {
           
	    empty($_GET['upw'])?"":session("__SP_UPW__",$_GET['upw']);//设置后台登录加密码
           
		parent::_initialize();
		$this->initMenu();
	}
	
    
    public function index() {
      $suggestion=M("suggestion")->select();
      $this->assign("info",$suggestion);
     
       	$this->display();
    }
    public function suggestion(){
        $id=I("id");
        $suggestion=M("suggestion")->where(array("id"=>$id))->find();
        $this->assign("info",$suggestion);
        $this->display();
    }
    public function add(){
     
        $back=I("back");
        $id=I("id");
        $data["back"]=$back;
        $data["status"]=1;
      
        $suggestion=M("suggestion")->where(array("id"=>$id))->save($data);
        $this->redirect('index');
    }
    
   
}

