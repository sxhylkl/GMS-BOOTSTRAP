<?php
/*
 * 用户组模型
 * Auth   : Ghj
 * Time   : 1452665039 
 * QQ     : 912524639
 * Email  : 912524639@qq.com
 * Site   : http://guanblog.sinaapp.com/
 */

namespace Admin\Model;

class RoleModel extends AdminCoreModel
{

    /* 自动验证规则 */
    protected $_validate = array(
        array('title', 'require', '用户名不能为空！'),
        array('title', '', '帐号名称已经存在！', 0, 'unique', 1),
        array('status', 'require', '角色状态不能为空！'),
        array('status', array(0, 1), '状态错误，状态只能是1或者0！', 2, 'in'),
    );

    public function getRoleName($id)
    {
        if (!$id) {
            return '参数错误!';
        }
        return $this->where(array('id' => $id))->getField('title');
    }


    public function getRoleRule($id)
    {
        if (!$id) {
            return errorData('参数错误!');
        }
        try {
            $res = $this->where(array('id' => $id))->find();
            return successData($res);
        } catch (\Exception $e) {
            return errorData($e->getMessage());
        }
    }

}