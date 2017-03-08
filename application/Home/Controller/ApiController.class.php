<?phpnamespace Home\Controller;use Think\Controller;/** * todo 删除已经分离的方法 * get* * manage* */class ApiController extends CommonController {    public function _empty($name) {        $this->error('不存在接口:' . $name);    }    public function ulogin() {        $username = I("username");        $pwd = I("password");        if (empty($username)) {            $this->error("缺少参数username");        }        if (empty($pwd)) {            $this->error("缺少参数pwd");        }        if (preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $username)) {//手机号登录            $this->_do_mobile_login();        } else {            $this->_do_email_login(); // 用户名或者邮箱登录        }    }    // 处理前台用户手机登录    private function _do_mobile_login() {        $users_model = M('Member');        $where = array("member_state" => 1);        $where['mobile'] = I('post.username');        $password = I('post.password');        $result = $users_model->where($where)->find();        if (!empty($result)) {            if (sp_compare_password($password, $result['member_passwd'])) {                // session('user',$result);                //写入此次登录信息                $data = array(                    'token' => md5(time() . rand(10, 99)),                    'member_login_time' => date("Y-m-d H:i:s"),                    'member_login_ip' => get_client_ip(0, true),                );                $users_model->where(array('member_id' => $result["member_id"]))->save($data);                $info = $users_model->where(array('member_id' => $result["member_id"]))->find();                $this->success($info);//                $this->success("登录验证成功！", $redirect);            } else {                $this->error("密码错误！");            }        } else {            $this->error("用户名不存在或已被拉黑！");        }    }    // 处理前台用户邮件或者用户登录    private function _do_email_login() {        $username = I('post.username');        $password = I('post.password');        $where = array("member_state" => 1);        if (strpos($username, "@") > 0) {//邮箱登陆            $where['member_email'] = $username;        } else {            $where['member_name'] = $username;        }        $users_model = M('member');        $result = $users_model->where($where)->find();        //exit();        if (!empty($result)) {            if (sp_compare_password($password, $result['member_passwd'])) {                session('user', $result);                //写入此次登录信息                $data = array(                    'token' => md5(time() . rand(10, 99)),                    'member_login_time' => date("Y-m-d H:i:s"),                    'member_login_ip' => get_client_ip(0, true),                );                $users_model->where("member_id=" . $result["member_id"])->save($data);                $info = $users_model->where(array('member_id' => $result["member_id"]))->find();                $this->success($info);            } else {                $this->error("密码错误！");            }        } else {            $this->error("用户名不存在或已被拉黑！");        }    }    public function getVerify() {        $phone = I('phone');        if (empty($phone)) {            $this->error('缺少参数 phone');        }        if (!checkPhoneFormat($phone)) {            $this->error('手机号码格式错误');        }        if (S('phone_verify' . $phone)) {            $this->error('该手机近期已经发送过验证码，请稍后再试');        }        $verify = rand(1000, 9999);        if (false === $res = send_mobile_message($verify, $phone)) {            $this->error('发送失败:' . $res['message'] . '(' . $res['code'] . ')');        }        S('phone_verify' . $phone, $verify, 120);        $this->success($res);    }    //注册接口    public function register() {        $data = I("");        $mobile = I("mobile");        $pwd = I("member_passwd");        $repwd = I("remember_passwd");        $verify = I("verify");        $verify_s = S('phone_verify' . $mobile);        if (empty($mobile)) {            $this->error("缺少参数moblie");        }        if (empty($pwd)) {            $this->error("缺少参数password");        }        if (empty($repwd)) {            $this->error("缺少参数repassword");        }        if ($pwd !== $repwd) {            $this->error("密码不一致");        }        if (!$verify == $verify_s) {            $this->error("手机验证码错误或者已失效，请重新获取");        }        $token = md5(time() . rand(10, 99));        $data["token"] = $token;        $rules = array(            array('mobile', '', '手机号已经存在！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一        );        $Member = M("Member");        if (!$Member->validate($rules)->create()) {            $this->error($Member->getError());        } else {            $data["member_passwd"] = sp_password($pwd);            $result = $Member->add($data);            if ($result) {                $this->success($data);            } else {                $this->error("注册失败");            }        }    }        //返回文章详情    public function conment() {        $id = I("id");        $term_relationships = M("term_relationships")->where(array("term_id" => $id))->field("object_id")->select();        $arr = array();        foreach ($term_relationships as $vv) {            $arr[] = $vv["object_id"];        }               $where["id"] = array("in", $arr);        $post = M("posts")->where($where)->select();        foreach($post as &$v){             $v["smeta"]=json_decode($v["smeta"],true);        }                         $this->success($post);    }    //返回首页列表    public function cat_nav() {        $where["parentid"] = array("neq", 0);        $nav = M("nav")->where($where)->select();        foreach ($nav as &$v) {            $v["href"] = unserialize($v["href"]);        }        $this->success($nav);    }    //返回二级导航列表页    public function second_nav() {        $id = I("id");        if (empty($id)) {            $this->error("缺少参数id");        }        $where["parentid"] = array("eq", $id);        $nav = M("nav")->where($where)->select();        foreach ($nav as &$v) {            $v["href"] = unserialize($v["href"]);        }        $this->success($nav);    }    //返回底部导航    public function foot_nav() {        $navs = M("nav")->where(array("parentid" => 0))->order("sort")->select();        foreach ($navs as &$v) {            $v["label"] = mb_substr($v["label"], 0, 2, 'utf-8');            $v["href"] = unserialize($v["href"]);        }        $this->success($navs);    }    public function getmemberBytoken() {        $minfo = $this->minfo();        $this->success($minfo);    }    //通过token拿用户信息    protected function minfo() {        $token = I('token');        $Member = M('Member');        if (empty($token)) {            $this->error('缺少参数token');        }        $map['token'] = $token;        if (false === $minfo = $Member->where($map)->find()) {            $this->error($Member->getError());        }        if (empty($minfo) && !empty($token)) {            $this->error('登录过期');        }        return $minfo;    }    //意见反馈    public function suggestion() {        $suggestion = M("suggestion")->select();        $this->success($suggestion);    }    //意见上报    public function suggestion_add() {        $userinfo = $this->minfo();        $user = empty($userinfo["nickname"]) ? $userinfo["mobile"] : $userinfo["nickname"];        $content = I("content");        $title = I("title");        $src=I("src");        $type=I("type");        $phone=I("phone");        $addr=I("addr");        if (empty($content)) {            $this->error("缺少参数content");        }        if (empty($title)) {            $this->error("缺少参数title");        }         if (empty($phone)) {            $this->error("缺少参数phone");        }         if (empty($addr)) {            $this->error("缺少参数addr");        }        if (empty($type)) {            $this->error("缺少参数type");        }        $data["name"] = $user;        $data["avatar"] = $userinfo["member_avatar"];        $data["content"] = $content;        $data["title"] = $title;        $data["uid"] = $user["member_id"];        $data["pic"]=$src;        $data["type"]=$type;        $data["phone"]=$phone;        $data["addr"]=$addr;        $data["addtime"] = time();        $suggesion = M("suggestion")->add($data);        $this->success("上报成功");    }    /**     * 上传文件 字段img     */    public function upload() {        $base64 = I('base64');               $arr=array();        foreach($base64 as $v){             $base64_image = str_replace(' ', '+', $v);        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行                     if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)) {            //匹配成功            if ($result[2] == 'jpeg') {                $image_name = uniqid() . '.jpg';                //纯粹是看jpeg不爽才替换的            } else {                $image_name = uniqid() . '.' . $result[2];            }            $image_file = './public/images/' . date('Y-m-d-') . '/' . $image_name;            //服务器文件存储路径            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))) {                $arr[]=$image_file;                           } else {                $this->error("上传失败");            }        } else {            $this->error("上传失败");        }        }    $this->success($arr);    }   //失物招领列表    public function shiwu(){        $shiwu=M("shiwuzholing")->select();        foreach($shiwu as &$v){            $v["addtime"]=  date("Y-m-d H:i", $v["addtime"]);            $v["time"]=date("Y-m-d", $v["addtime"]);        }        $this->success($shiwu);    }    //失物招领添加    public function shiwuadd(){        $name=I("name");        $addr=I("addr");        $src=I("src");        $thing=I("thing");        $phone=I("phone");        $weixin=I("weixin");        $type=I("type");        if(empty($type)){           $this->error("请选择类型");         }        if(empty($phone)){            $this->error("缺少参数phone");        }        if(empty($name)){          $this->error("缺少参数name");          }         if(empty($addr)){          $this->error("缺少参数addr");          }         if(empty($thing)){          $this->error("缺少参数thing");          }        $data["name"]=$name;        $data["addr"]=$addr;        $data["src"]=$src;        $data["thing"]=$thing;        $data["addtime"]=time();        $data["type"]=$type;        $data["phone"]=$phone;        $data["weixin"]=$weixin;        $shiwuzholing=M("shiwuzholing")->add($data);        $this->success("添加成功");    }    //意见反馈搜索    public function yijiansearch(){        $keywords=I("keywords");        if(!empty($keywords)){            $where["name|title"]=array("like","%$keywords%");        }        $suggestion=M("suggestion")->where($where)->select();         $this->success($suggestion);    }}