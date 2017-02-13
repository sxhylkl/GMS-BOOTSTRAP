<?php

/*
 * 公共方法控制器
 * Auth : Ghj
 * Time : 2015年4月11日
 * QQ : 912524639
 * Email : 912524639@qq.com
 * Site : http://guanblog.sinaapp.com/
 */
namespace Admin\Controller;

use Common\Controller\CoreController;
use Service\Utils\GroupInfoUtils;

class FunctionController extends CoreController
{

    //核心继承
    protected function _initialize()
    {
        //继承CoreController的初始化函数
        parent::_initialize();
        if (session(C('AUTH_KEY'))) {
            return false;
        }
    }

    /*
     * 根据传入config的名称获取config然后返回
     * Auth : Ghj
     * Time : 2015年4月16日
     */
    public function get_config($cname = '')
    {
        if ($cname == '') {
            $cname = I('get.cname');
        }
        $extra_option_arr = explode('|', $cname);
        $ops = C($extra_option_arr [0]);
        if ($extra_option_arr[1] == '') {
            $ops_arr = explode('|', $ops);
            $data_ls ['type'] = '';
            $data_ls ['value'] = '请选择一个选项';
            $data [] = $data_ls;
            foreach ($ops_arr as $ops_arr_val) {
                $val_ls = explode(':', $ops_arr_val);
                $data_ls ['type'] = $val_ls[0];
                $data_ls ['value'] = $val_ls[1];
                $data [] = $data_ls;
            }
        } else {
            $data_ls [$extra_option_arr [1]] = '';
            $data_ls [$extra_option_arr [2]] = '请选择一个选项';
            $data [] = $data_ls;
            $ops_arr = explode('|', $ops);
            foreach ($ops_arr as $ops_arr_val) {
                $val_ls = explode(':', $ops_arr_val);
                $data_ls [$extra_option_arr [1]] = $val_ls[0];
                $data_ls [$extra_option_arr [2]] = $val_ls[1];
                $data [] = $data_ls;
            }
        }
        $r_type = I('get.r_type');
        if ($r_type == 'json') {
            $this->ajaxReturn($data);
        } else {
            return $data;
        }
    }

    /*
     * 获取用户组树
     * Auth : Ghj
     * Time : 2015年4月16日
     */
    public function get_auth_group($_pid = '0')
    {
        if ($_pid == '0') {
            $_pid = I('get.pid', 0);
        }
        $map ['status'] = 1;

        //差别显示
        $accout_state = $this->UserInfo['system_user'];
        if ($accout_state > 0) {
            $map ['editable'] = 1;
            $map ['_logic'] = 'and';
        }
        $_list = M('AuthGroup')->where($map)->order('id asc')->field('id,title as text')->select();
        $r_type = I('get.r_type');
        if ($r_type == 'json') {
            $this->ajaxReturn($_list);
        } else {
            return $_list;
        }
    }


    /*
     * 获取节点树
     * Auth : Ghj
     * Time : 2015年4月16日
     */
    public function get_auth_rule_extend($_pid = '0')
    {
        if ($_pid == '0') {
            $_pid = I('get.pid', 0);
        }
        $map ['status'] = 1;
        $_list = M('AuthRule')->where($map)->order('sort asc')->getField('id,pid,title as text,icon');

//		获取当前用户角色，移除其中的某些项

        foreach ($_list as $key => $_list_one) {
            $_list[$key]['iconCls'] = $_list_one['icon'];
        }
        if ($_pid == '-1') {
            $_list [] = array(
                'id' => '0',
                'pid' => '-1',
                'text' => '根节点',
                'iconCls' => 'iconfont icon-viewlist'
            );
            $data = list_to_tree($_list, 'id', 'pid', 'children', '-1');
        } else {
            $data = list_to_tree($_list, 'id', 'pid', 'children');
        }
        $r_type = I('get.r_type');
        if ($r_type == 'json') {
            echo json_encode($data);
        } else {
            return $data;
        }
    }

    /*
     * 获取节点树
     * Auth : Ghj
     * Time : 2015年4月16日
     */
    public function get_auth_rule($_pid = '0')
    {
        if ($_pid == '0') {
            $_pid = I('get.pid', 0);
        }
        $map ['status'] = 1;
        $accout_state = $this->UserInfo['system_user'];
        if ($accout_state > 0) {
            $map ['system_jurisdiction'] = 1;
            $map ['_logic'] = 'and';
        }
        $_list = M('AuthRule')->where($map)->order('sort asc')->getField('id,pid,title as text');

        if ($_pid == '-1') {
            $_list [] = array(
                'id' => '0',
                'pid' => '-1',
                'text' => '根节点',
//					'iconCls'=>'iconfont icon-viewlist'
            );
            $data = list_to_tree($_list, 'id', 'pid', 'children', '-1');
        } else {
            $data = list_to_tree($_list, 'id', 'pid', 'children');
        }
        $r_type = I('get.r_type');
        if ($r_type == 'json') {
            echo json_encode($data);
        } else {
            return $data;
        }
    }

    /*
     * 获取图标
     * Auth : Ghj
     * Time : 2015年4月16日
     */
    public function get_icon($_pid = '0')
    {
        $iconfont = file_get_contents('./Public/Static/Font/iconfont.css');
        $preg = '/.(.*):before/U';
        preg_match_all($preg, $iconfont, $arr);
        foreach ($arr[1] as $one) {
            $data_ls['id'] = 'iconfont ' . $one;
            $data_ls['text'] = $one;
            $data[] = $data_ls;
        }
        $r_type = I('get.r_type');
        if ($r_type == 'json') {
            echo json_encode($data);
        } else {
            return $data;
        }
    }




    /**
     * 拉取行为列表
     */

    public function searchAction()
    {

        $dao_action = M('action');

        $data_action = $dao_action->field('id as word_id,title as word')->select();

        echo json_encode($data_action);

    }

    /**
     * 拉取角色列表
     */
    public function getAuthList()
    {

        $accout_state = $this->UserInfo['system_user'];
        if ($accout_state > 0) {
            $map ['visible'] = 1;
        } else {
            $map['visible'] = array('in', "1,0");
        }

        $dao = D('auth_group');
        $data = $dao->where($map)->field("id,title as text")->select();
        echo json_encode($data);
    }


}
