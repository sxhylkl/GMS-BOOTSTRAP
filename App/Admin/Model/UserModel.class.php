<?php 
/*
 * 用户管理模型
 * Auth   : Ghj
 * Time   : 1444386899 
 * QQ     : 912524639
 * Email  : 912524639@qq.com
 * Site   : http://guanblog.sinaapp.com/
 */
 
namespace Admin\Model;
use Exception;
use Think\Model;

class UserModel extends Model{

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'),
        array('password', 'require', '密码不能为空！', 0, 'regex', 1),
        array('eque_password', 'require', '确认密码不能为空！', 0, 'regex', 1),
        array('username', '', '帐号名称已经存在！', 0, 'unique', 1),
        array('status', array(0, 1), '状态错误，状态只能是1或者0！', 2, 'in'),
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('update_time', 'time', 3, 'function'),
        array('password', 'md5', 3, 'function'),
    );


    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return mixed 返回登录成功的用户信息
     * @throws \Exception
     */
    public function login($username, $password)
    {
        $password = md5($password);
        if (empty ($username) || empty ($password)) {
            throw new \Exception("用户名密码不能为空");
        }
        $map = array(
            'username' => trim($username),
            'password' => trim($password),
        );
        $UserInfo = $this->where($map)
            ->field('id,username,head_img,role_id,group_id,status,system_user')
            ->find();
        if ($UserInfo) {
            if ("1" == $UserInfo['status']) {

                $AG = new AuthRoleModel();

                $AG_Data = $AG->getAuthGroupInfo($UserInfo['role_id']);

                if ($AG_Data) {
                    $UserInfo['group_title'] = $AG_Data['title'];
                }
                session(C('AUTH_KEY'), $UserInfo['id']);
                session('UserInfo', $UserInfo);
                action_log('Admin_Login', 'User', $UserInfo ['id']);
//                return $UserInfo;
            } else {
                throw new \Exception("用户被禁用");
            }
        } else {
            throw new \Exception("用户不存在或密码不正确");
        }
    }

}