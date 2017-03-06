<?phpnamespace Home\Controller;use Think\Controller;/** * todo 删除已经分离的方法 * get* * manage* */class ApiController extends CommonController {     public function _empty($name) {        $this->error('不存在接口:' . $name);    }    public function ulogin() {        $username=I("username");        $pwd=I("password");              if(empty($username)){            $this->error("缺少参数username");        }         if(empty($pwd)){            $this->error("缺少参数pwd");        }            if(preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $username)){//手机号登录             	    $this->_do_mobile_login();    	}else{    	    $this->_do_email_login(); // 用户名或者邮箱登录    	}    }     // 处理前台用户手机登录    private function _do_mobile_login(){        $users_model=M('Member');        $where = array("member_state"=>1);        $where['mobile']=I('post.username');        $password=I('post.password');        $result = $users_model->where($where)->find();              if(!empty($result)){            if(sp_compare_password($password, $result['member_passwd'])){               // session('user',$result);                //写入此次登录信息                                 $data = array(                    'token'=>md5(time() . rand(10, 99)),                    'member_login_time' => date("Y-m-d H:i:s"),                    'member_login_ip' => get_client_ip(0,true),                );                $users_model->where(array('member_id'=>$result["member_id"]))->save($data);                $info= $users_model->where(array('member_id'=>$result["member_id"]))->find();                  $this->success($info);//                $this->success("登录验证成功！", $redirect);            }else{                $this->error("密码错误！");            }        }else{            $this->error("用户名不存在或已被拉黑！");        }    }   // 处理前台用户邮件或者用户登录    private function _do_email_login(){        $username=I('post.username');        $password=I('post.password');        $where = array("member_state"=>1);        if(strpos($username,"@")>0){//邮箱登陆            $where['member_email']=$username;        }else{            $where['member_name']=$username;        }        $users_model=M('member');        $result = $users_model->where($where)->find();               //exit();        if(!empty($result)){            if(sp_compare_password($password, $result['member_passwd'])){                session('user',$result);                //写入此次登录信息                $data = array(                    'token'=>md5(time() . rand(10, 99)),                    'member_login_time' => date("Y-m-d H:i:s"),                    'member_login_ip' => get_client_ip(0,true),                );                $users_model->where("member_id=".$result["member_id"])->save($data);                  $info= $users_model->where(array('member_id'=>$result["member_id"]))->find();                $this->success($info);            }else{                $this->error("密码错误！");            }        }else{            $this->error("用户名不存在或已被拉黑！");        }    }     public function getVerify() {        $phone = I('phone');        if (empty($phone)) {            $this->error('缺少参数 phone');        }        if (!checkPhoneFormat($phone)) {            $this->error('手机号码格式错误');        }        if (S('phone_verify' . $phone)) {            $this->error('该手机近期已经发送过验证码，请稍后再试');        }        $verify = rand(1000, 9999);        if (false === $res = send_mobile_message($verify, $phone)) {            $this->error('发送失败:' . $res['message'] . '(' . $res['code'] . ')');        }        S('phone_verify' . $phone, $verify, 60);        $this->success($res);    }    //注册接口    public function register(){        $data=I("");        $mobile=I("mobile");        $pwd=I("member_passwd");        $repwd=I("remember_passwd");        $verify=I("verify");        $verify_s=S('phone_verify' . $phone);        if(empty($mobile)){            $this->error("缺少参数moblie");        }        if(empty($pwd)){             $this->error("缺少参数password");        }        if(empty($repwd)){             $this->error("缺少参数repassword");        }        if($pwd!==$repwd){            $this->error("密码不一致");        }        if($verify!==$verify_s){             $this->error("手机验证码错误或者已失效，请重新获取");        }         $token=md5(time() . rand(10, 99));           $data["token"]=$token;           $rules = array(               array('mobile','','手机号已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一           );               $Member = M("Member");        if(!$Member->validate($rules)->create()){           $this->error($Member->getError());       }else{          $data["member_passwd"]=sp_password($pwd);           $result=$Member->add($data);           if($result){                              $this->success($data);           }else{               $this->error("注册失败");           }       }    }    //返回文章详情    public function conment(){        $id=I("id");        $id=5;        $Model=M();         $posts = $Model->table('__POSTS__ p')                        ->join('__TERM_RELATIONSHIPS__ tr on tr.object_id=p.id ', 'left')                        ->join('__TERMS__ t on t.term_id=tr.term_id', 'left')                                              ->where(array('t.term_id' => $id))->field('p.*')->select();         if($posts){             $this->success($posts);          }else{             $this->error("没有数据");         }            }    //返回首页列表    public function cat_nav(){        $nav=M("nav")->select();        foreach ($nav as &$v){            $v["href"]=unserialize($v["href"]);        }        $this->success($nav);    }    public function sss(){       $minfo= $this->minfo();        $this->success($minfo);    }    //通过token拿用户信息    protected function minfo() {            $token = I('token');        $Member = M('Member');                  if (empty($token)) {                $this->error('缺少参数token');            }               $map['token'] = $token;               if (false === $minfo = $Member->where($map)->find()) {            $this->error($Member->getError());        }        if (empty($minfo) && !empty($token)) {            $this->error('登录过期');        }        return $minfo;    }     }