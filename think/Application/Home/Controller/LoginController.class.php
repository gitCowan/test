<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3 0003
 * Time: 上午 9:49
 */
namespace Home\Controller;
use Home\Model\TokenModel;
use Think\Controller;
class LoginController extends Controller
{
    public function login(){
        $this->display();
    }

    public function check(){
        //实例化模型
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        if(!trim($nickname)){
            return show('0','用户名不能为空');
        }
        if(!trim($password)){
            return show('0','密码不能为空');
        }
        $ress = D('Token')->getLogin($nickname);
        if($ress['sex'] == $password){
            echo "登录成功";
        }
    }

}