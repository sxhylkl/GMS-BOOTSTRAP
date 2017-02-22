<?php
/*
 * 用户管理模型
 * Auth   : li
 * QQ     : 184117183
 * Email  : 184117183@qq.com
 */

namespace Admin\Model;

class UserModel extends AdminCoreModel
{

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
        array('system_user', '0', 3),
    );

    protected function searchFields()
    {
        return 'id,username,role_id,group_id,phone,email,status,remark';
    }

    protected function formatData($lists)
    {
        foreach ($lists as $k => $list) {
            $lists[$k]['status'] = getStatus()[$list['status']];
            $lists[$k]['role_id'] = (new RoleModel())->getRoleName($list['role_id']);
        }
        return $lists;
    }


    protected function search()
    {
        $map = array();
        $post_data = I('post.');
        /* 名称：用户名 字段：username 类型：string*/
        if (isset($post_data['s_username']) && $post_data['s_username'] != '') {
            $map['id'] = array('in', $post_data['s_username']);
        }
        return $map;
    }


    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return mixed 返回登录成功的用户信息
     * @throws \Exception
     */
    public function login($username, $password)
    {

        if (empty ($username) || empty ($password)) {
            throw new \Exception("用户名密码不能为空");
        }
        $password = md5($password);
        $map = array(
            'username' => trim($username),
            'password' => trim($password),
        );
        $UserInfo = $this->where($map)
            ->field('id,username,head_img,role_id,group_id,status,system_user')
            ->find();
        if ($UserInfo) {
            if ("0" == $UserInfo['status']) {
//                $role = new RoleModel();
//
//                $roleData = $role->getRoleRule($UserInfo['role_id']);
//
//                if ($roleData) {
//                    $UserInfo['user_role'] = $roleData['title'];
//                }
                session(C('AUTH_KEY'), $UserInfo['id']);
                session('UserInfo', $UserInfo);
                action_log('Admin_Login', 'User', $UserInfo ['id']);
            } else {
                throw new \Exception("用户被禁用");
            }
        } else {
            throw new \Exception("用户不存在或密码不正确");
        }
    }


}