<?php

namespace Admin\Controller;

/**
 * 后台首页控制器
 */
class IndexController extends AdminCoreController
{

    /**
     * 显示后台首页页面
     */
    public function index()
    {
//        dump($this->get_menu());
        $this->assign('Menu', $this->getMenu());
        $this->display('index');
    }

    /**
     * 显示清除缓存页面
     */
    public function cache()
    {
        $this->display();
    }

}