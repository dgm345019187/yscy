<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController;

class ListController extends HomebaseController {

	// 前台文章列表
	public function index() {
	    $term_id=I('get.id',0,'intval');
           
		$term=sp_get_term($term_id);
		
		if(empty($term)){
		    header('HTTP/1.1 404 Not Found');
		    header('Status:404 Not Found');
		    if(sp_template_file_exists(MODULE_NAME."/404")){
		        $this->display(":404");
		    }
		    return;
		}
		
		$tplname=$term["list_tpl"];
    	$tplname=sp_get_apphome_tpl($tplname, "list");
        $pid=I('get.pid',0,'intval');
          $navs=M("nav")->where(array("parentid"=>$pid))->order("id")->select();
         foreach ($navs as $key=>$nav){
		$href=htmlspecialchars_decode($nav['href']);
		$hrefold=$href;
		if(strpos($hrefold,"{")){//序列 化的数据
			$href=unserialize(stripslashes($nav['href']));
            $href=strtolower(leuu($href['action'],$href['param']));
			$href=preg_replace("/\/$default_app\//", "/",$href);
			$href=preg_replace("/$g=$default_app&/", "",$href);
		}else{
			if($hrefold=="home"){
				$href=__ROOT__."/";
			}else{
				$href=$hrefold;
			}
		}
		$nav['href']=$href;
		$navs[$key]=$nav;
	}
       // print_r($term);die();
        $this->assign("pid",$navs);
       
        $this->assign("id",$pid);
        $flag=I('get.flag',0,'intval');
       
        $this->assign("flag",$flag);
    	$this->assign($term);
    	$this->assign('cat_id', $term_id);
    	$this->display(":$tplname");
	}
	
	// 文章分类列表接口,返回文章分类列表,用于后台导航编辑添加
	public function nav_index(){
		$navcatname="文章分类";
        $term_obj= M("Terms");

        $where=array();
        $where['status'] = array('eq',1);
        $terms=$term_obj->field('term_id,name,parent')->where($where)->order('term_id')->select();
		$datas=$terms;
		$navrule = array(
		    "id"=>'term_id',
            "action" => "Portal/List/index",
            "param" => array(
                "id" => "term_id"
            ),
            "label" => "name",
		    "parentid"=>'parent'
        );
		return sp_get_nav4admin($navcatname,$datas,$navrule) ;
	}
        public function liebiao(){
            $id=I("id");
          $navs=M("nav")->where(array("parentid"=>$id))->order("id")->select();
         foreach ($navs as $key=>$nav){
		$href=htmlspecialchars_decode($nav['href']);
		$hrefold=$href;
		if(strpos($hrefold,"{")){//序列 化的数据
			$href=unserialize(stripslashes($nav['href']));
            $href=strtolower(leuu($href['action'],$href['param']));
			$href=preg_replace("/\/$default_app\//", "/",$href);
			$href=preg_replace("/$g=$default_app&/", "",$href);
		}else{
			if($hrefold=="home"){
				$href=__ROOT__."/";
			}else{
				$href=$hrefold;
			}
		}
		$nav['href']=$href;
		$navs[$key]=$nav;
	}
          //print_r($navs);die();
          $this->assign("list",$navs);
          $this->assign("id",$id);
            $this->display(":liebiao");
        }
        public function yijian(){
             $pid=I('get.pid',0,'intval');
          $navs=M("nav")->where(array("parentid"=>$pid))->order("id")->select();
         foreach ($navs as $key=>$nav){
		$href=htmlspecialchars_decode($nav['href']);
		$hrefold=$href;
		if(strpos($hrefold,"{")){//序列 化的数据
			$href=unserialize(stripslashes($nav['href']));
            $href=strtolower(leuu($href['action'],$href['param']));
			$href=preg_replace("/\/$default_app\//", "/",$href);
			$href=preg_replace("/$g=$default_app&/", "",$href);
		}else{
			if($hrefold=="home"){
				$href=__ROOT__."/";
			}else{
				$href=$hrefold;
			}
		}
		$nav['href']=$href;
		$navs[$key]=$nav;
	}
       // print_r($term);die();
        $this->assign("pid",$navs);
         $this->assign("id",$pid);
           $suggestion=M("suggestion")->select();
           $this->assign("infos",$suggestion);
           //print_r($suggestion);die();
           $this->display(":yijian");
        }
        public function yijianxiangqing(){
            $id=I("id");
            $suggestion=M("suggestion")->where(array("id"=>$id))->find();
            $this->assign("suggestion",$suggestion);
            //print_r($suggestion);die();
           $this->display(":yijianxiangqing");
        }
}
