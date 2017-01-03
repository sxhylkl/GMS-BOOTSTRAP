<?php

namespace Admin\Controller;

use Admin\Model\UserModel;
use Common\Controller\CoreController;

class PublicController extends CoreController
{

    public function login($username="", $password="")
    {
        if (is_login()) {
            $this->redirect('Admin/Index/index');
        } else {
            if (IS_POST) {
                $username = I("post.username", "", "trim");
                $password = I("post.password", "", "trim");

                if (empty ( $username ) || empty ( $password )) {
                    $this->ajaxReturn(errorData('用户名或密码不能为空!'));
                }
                $userModel = new UserModel();
                try {
                    $userModel->login($username, $password);
                } catch (\Exception $e) {
                    $this->ajaxReturn(errorData($e->getMessage()));
                }
                $this->ajaxReturn(U("Admin/Index/index"));
            } else {
                $this->display();
            }
        }
    }


    /* 退出登录 */
    public function logout()
    {
        if (!is_login()) {
            $this->error("尚未登录", U(C('AUTH_USER_GATEWAY')));
        } else {
            action_log('Admin_Logout', 'User', is_login());
            session(null);
            cookie('rw', null);
            if (session(C('AUTH_KEY'))) {
                $this->error("退出失败", U(C('AUTH_USER_INDEX')));
            } else {
                header("Location: " . U(C('AUTH_USER_GATEWAY')));
            }
        }
    }

}
