<?php 
/*
 * 用户控制器
 * Auth   : li
 * QQ     : 184117183
 * Email  : 184117183@qq.com
 */
 
namespace Admin\Controller;

use Admin\Model\UserModel;

class UserController extends AdminCoreController {
	
	//系统默认模型
    protected function _initialize() {
		//继承初始化方法
		parent::_initialize ();
        $this->Model = new UserModel();
    }

    /**
     * 图片上传文件名称处理
     * @return string   返回文件名称字符串
     */

    protected function fileUpload(){
        $result = "";
        if(0 == intval($_FILES['user_photo']['size'])){
            return $result;
        }
        else{
            $fileInfo = $_FILES['user_photo'];
            $typeArr = explode("/", $fileInfo['type']);
            if (strtolower($typeArr[0]) !== "image") {
                return $result;
            }
            $suffix = "." . $typeArr[1];
            $fileName = md5_file($fileInfo["tmp_name"]) . $suffix;
            $path = UPLOAD_PATH. $fileName;
            $states = true;
            if (!file_exists($path)) {
                $states = move_uploaded_file($fileInfo["tmp_name"], $path);
            }
            if ($states) {
                $result = $fileName;
            }
        }
        return $result;
    }



    /* 添加
     * Auth   : Ghj
     * Time   : 2016年01月10日 
     **/
	public function add(){

        $result = array();
        $result['Result'] = false;
        $result['Code'] = 200;

		if(IS_POST){

			$post_data=I('post.');

            //判断上传图片大小是否为0
            if(0 != intval($_FILES['user_photo']['size'])){
                $post_data['head_img'] = $this->fileUpload();
            }
            else{
                $post_data['head_img'] = "";
            }


            unset($post_data['id']);

			$data=$this->Model->create($post_data);
			if($data){

				if($post_data['password'] === $post_data['eque_password']){
					$res = $this->Model->add($data);
					if(false !== $res){
						action_log('Add_User', 'User', $res);
                        $result['Result'] = true;
                        $result['Msg'] = '数据添加成功';
//						$this->success ( "操作成功！",U('index'));
					}else{
						$error = $this->Model->getError();
//						$this->error($error ? $error : "操作失败！");

                        $result['Msg'] = $error ? $error : "操作失败！";
					}
				}
				else{
					$error = $this->Model->getError();
//					$this->error($error ? $error : "密码与确认密码不相同！");
                    $result['Msg'] = $error ? $error : "操作失败！";
				}
			}
			else{
                $error = $this->Model->getError();
//                $this->error($error ? $error : "操作失败！");

                $result['Msg'] = $error ? $error : "操作失败！";
			}

            echo json_encode($result);
//			$this->ajaxReturn($result);
		}
	}
	
    /* 编辑
     * Auth   : Ghj
     * Time   : 2016年01月10日 
     **/
	public function edit(){
		if(IS_POST) {
            $post_data = I('post.');
            $result = array();
            $result['Result'] = false;
            $result['Code'] = 200;


            //判断上传图片大小是否为0
            if(0 != intval($_FILES['user_photo']['size'])){
                $post_data['head_img'] = $this->fileUpload();
            }
            else{
                $post_data['head_img'] = $this->Model->where(array(
                    'id' => $post_data['id']
                ))->field('head_img')->find()['head_img'];
            }

            $data = $this->Model->create($post_data);
            if ($data) {

                unset($data['password']);
                $res = $this->Model->where(array('id' => $post_data['id']))->save($data);
                if (false !== $res) {

                    $result['Result'] = true;
                    $result['Msg'] = '更新成功';
                    action_log('Edit_User', 'User', $post_data['id']);
//                    $this->success("操作成功！", U('index'));
                } else {
                    $error = $this->Model->getError();
//                    $this->error($error ? $error : "操作失败！");
                    $result['Msg'] = $error ? $error : "操作失败！";
                }
            }
            else {
                $error = $this->Model->getError();
//                $this->error($error ? $error : "操作失败！");
                $result['Msg'] = $error ? $error : "操作失败！";
            }

//            $this->ajaxReturn($result);
                echo json_encode($result);
        }

	}

	public function userinfo(){
        if(IS_POST){
            $dao = M('user');
            $data = null;
            $map = array ();
            $post_data=I('post.');
            if($post_data['id']!=''){
                $map['id']=array('in', $post_data['id']);
            }
            else{
                $map['id']=array('in', $this->UserInfo['id']);
            }
            $data = $dao->where ( $map )->field(
                'id,username,email,phone,head_img,status,group_ids,service_group_id,remark'
            )->find();

            $result['Code'] = 200;
            $result['Result'] = $data;
            echo json_encode($result);
        }
        else{
            $dao = M('user_group_view');
            $_info=$this->UserInfo;
            $_info = $dao->where(array('id'=>$_info['id']))->find();
            $this->assign('_info', $_info);
            $this->display();
        }


	}

	
    /* 删除
     * Auth   : Ghj
     * Time   : 2016年01月10日 
     **/
	public function del(){
		$id=I('get.id');
		empty($id)&&$this->error('参数不能为空！');
		$res=$this->Model->delete($id);
		if(!$res){
			$this->error($this->Model->getError());
		}else{
			action_log('Del_User', 'User', $id);
			$this->success('删除成功！');
		}
	}

    /**
     * 修改密码
     */
    public function updatePassword(){
		if(IS_POST){

		    $result = array();
            $result['Code'] = 200;
            $result['Result'] = false;
			$post_data=I('post.');
            if('' != $post_data['old_password']){

                if('' != $post_data['new_password']){

                    if('' != $post_data['eque_password']){

                        if($post_data['eque_password'] == $post_data['new_password']){

                            $_info = $this->Model->where(array('id'=>$this->UserInfo['id']))->find();
                            if($_info['password'] == md5($post_data['old_password'])){


//                                $post_data['password']=md5($post_data['new_password']);此处重复加密,故而注释
                                
                                $post_data['password']=$post_data['new_password'];
                                $data=$this->Model->create($post_data);
                                if($data){

                                    unset($data['id']);

                                    $res = $this->Model->where(array('id'=>$post_data['id']))->save($data);
                                    if(false !== $res){

                                        action_log('Edit_User', 'User', $post_data['id']);

                                        $result['Result'] = true;

                                        $result['Msg'] = '重置密码成功';


//                                        $this->success ( "操作成功！",U('index'));
                                    }else{
//                                        $error = $this->Model->getError();
//                                        $this->error($error ? $error : "操作失败！");
                                        $result['Msg'] = '操作失败';

                                    }
                                }
                                else{
//                                    $error = $this->Model->getError();
//                                    $this->error($error ? $error : "操作失败！");

                                    $result['Msg'] = '操作失败';
                                }

                            }
                            else{
                                $result['Msg'] = '原密码错误';
                            }
                        }
                        else{
                            $result['Msg'] = '两次输入的密码不一致';
                        }
                    }
                    else{
                        $result['Msg'] = '请输入确认密码';
                    }

                }
                else{

                    $result['Msg'] = '请输入新密码';

                }
            }
            else{

                $result['Msg'] = '请输入原密码';

            }


            echo json_encode($result);
		}

    }

    public function updatePassword1(){
        if(IS_POST) {

            $result = array();
            $result['Code'] = 200;
            $result['Result'] = false;
            $post_data = I('post.');

            if ('' != $post_data['new_password']) {

                if ('' != $post_data['eque_password']) {

                    if ($post_data['eque_password'] == $post_data['new_password']) {


                        $post_data['password'] = $post_data['new_password'];
                        $data = $this->Model->create($post_data);
                        if ($data) {

                            unset($data['id']);

                            $res = $this->Model->where(array('id' => $post_data['id']))->save($data);
                            if (false !== $res) {

                                action_log('Edit_User', 'User', $post_data['id']);

                                $result['Result'] = true;

                                $result['Msg'] = '更新密码成功';

                            } else {

                                $result['Msg'] = '操作失败';

                            }
                        } else {

                            $result['Msg'] = '操作失败';
                        }

                    } else {
                        $result['Msg'] = '两次输入的密码不一致';
                    }
                } else {
                    $result['Msg'] = '请输入确认密码';
                }

            } else {

                $result['Msg'] = '请输入新密码';

            }


            echo json_encode($result);
        }
    }
}