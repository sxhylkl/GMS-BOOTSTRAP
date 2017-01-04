<?php

namespace Admin\Controller;

use Admin\Model\UserModel;
use Common\Controller\CoreController;
use Think\Exception;

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
                $userModel = new UserModel();
                try {
                    $userModel->login($username, $password);
                    echo json_encode(successData(U("Admin/Index/index")));
                } catch (\Exception $e) {
                    echo json_encode(errorData($e->getMessage()));
                }
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
