<?php
namespace Common\Model;
use Common\Model\CommonModel;
class MemberModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('member_name', 'require', '用户名称不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
		array('member_passwd', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),
		array('member_name', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('member_passwd', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('member_name','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
	    array('mobile','','手机号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证mobile字段是否唯一
		array('member_email','require','邮箱不能为空！',0,'regex',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		array('member_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		array('member_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	protected $_auto = array(
	    array('member_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    array('member_birthday','',CommonModel::MODEL_UPDATE,'ignore')
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['member_passwd']) && strlen($data['member_passwd'])<25){
			$data['member_passwd']=sp_password($data['member_passwd']);
		}
	}
	
}

