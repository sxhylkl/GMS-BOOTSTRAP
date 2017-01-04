<?php
namespace Admin\Controller;
use Common\Controller\CoreController;
use Common\Libs\Auth;

class AdminCoreController extends CoreController {
	//后台核心继承
    protected function _initialize() {
		//继承CoreController的初始化函数
        parent::_initialize();
        if(!is_login()){
            redirect(U(C('AUTH_USER_GATEWAY')));
        }
	}

	//后台菜单
	protected function get_menu(){
		//获取后台菜单缓存
		$menu=session('Menu');
		//如果缓存为空，即初次登录
		if(count($menu)!=999){

			if (in_array ( session ( C ( 'AUTH_KEY' ) ), C ( 'AUTH_ADMIN' ) )) {//如果认证key存在超级管理组配置中,不读取用户权限直接读取全部可显示菜单
				$map = array (
						'hide' => 0,
						'status' => 1
				);
			} else {//如果认证key不存在超级管理组配置中,读取用户权限,根据权限获取用户组
				//实例化Auth权限管理类
				$auth = new Auth();
				//获取当前用户 所在的所有组（即一个用户可以存在于多个用户组中）
				$groups = $auth->getRoles(session(C('AUTH_KEY')));
				$ids = array ();
				if(count($groups)<1){
                    //没有任何权限
                    //重定向到登出函数
                redirect(U('Admin/Public/logout'));
                //重定向到登录界面
                redirect(U(C('AUTH_USER_GATEWAY')));

//					$this->error ( '你没有系统的任何权限！',U('Public/logout'));
				}
				foreach ( $groups as $g ) {
					$ids = array_merge ( $ids, explode ( ',', trim ( $g ['rules'], ',' ) ) );
				}
				$ids = array_unique ( $ids );
				$map = array (
						'id' => array ('in',$ids ),
						'hide' => 0,
						'status' => 1
				);
			}
			//根据前面生成的查询条件 读取用户组所有权限规则
			$rules = M ( 'AuthRule' )->where ( $map )->field ( 'id,pid,name,title,icon,type' )->order ( 'sort asc' )->select ();
			foreach ( $rules as $k=>$rule ) {
				$rules[$k]['url']=U($rule['name']);
			}
			$menu = list_to_tree2 ( $rules, $pk = 'id', $pid = 'pid', 'children' );
			session ( 'Menu', null );
			session('Menu',$menu);
		}
		return $menu;
	}
}