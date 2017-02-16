<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/20 0020
 * Time: 下午 3:43
 */
namespace Home\Controller;
use Think\Controller;
use Home\Controller\WeixinController;


class AccreditController extends Controller
{
    //以下session
    public function index()
    {
        $weixin = new WeixinController();
        $code = $_GET['code'];
        $_SESSION['code'] = $code;
        $appid = "wx1974c1f0f7d6ca40";
        $secret = "900d0e4542b0fd7ca12d5abcc0fda90d";
        if($_SESSION['code'] != NULL){
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $_SESSION['code'] . "&grant_type=authorization_code";
        }else {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        }
        $res = $weixin->http_curl($url,'get','json');
        //print_r($res);

        //刷新access_token
        $_SESSION['refresh_token'] = $res['refresh_token'];
        if($_SESSION['refresh_token'] != NULL) {
            $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $_SESSION['refresh_token'];
        }else{
            $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $res['refresh_token'];
        }
        $resu = $weixin->http_curl($urls,'get','json');
        //print_r($resu);


        //获取用户资料
        $_SESSION['access_token'] = $res['access_token'];
        $_SESSION['openid'] = $resu['openid'];
        if($_SESSION['access_token'] != NULL && $_SESSION['openid'] != NULL) {
            $urlss = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $_SESSION['access_token'] . "&openid=" . $_SESSION['openid'] . "&lang=zh_CN";
        }else{
            $urlss = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $res['access_token'] . "&openid=" . $resu['openid'] . "&lang=zh_CN";
        }
        $resul = $weixin->http_curl($urlss,'get','json');
        M("token")->add($resul);
        $this->assign("resul",$resul);
        $this->display();
    }

    //首次进入判断用户是否存在  否则执行index方法将用户信息存入数据库中
    public function ifnull()
    {
        $code = $_GET['code'];
        $res = $this->access_token($code);
        $openid = $res['openid'];
        $con = M("token")->where(array("openid"=>$openid))->find();
        if($con == NULL){
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FAccredit%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
            header("Location: $url");
            //$con = $this->index($code);
        }
        $this->assign("resul",$con);
        $this->display("index");
    }

    //根据code获取用户refresh_token
    public function refresh_token($code = '')
    {
        //$code = $_GET['code'];
        $appid = "wx1974c1f0f7d6ca40";
        $secret = "900d0e4542b0fd7ca12d5abcc0fda90d";
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $weixin = new WeixinController();
        $res = $weixin->http_curl($url,'get','json');
        return $res;
    }

    //刷新access_token
    public function access_token($code = '')
    {
        $weixin = new WeixinController();
        $appid = "wx1974c1f0f7d6ca40";
        $res = $this->refresh_token($code);
        $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $res['refresh_token'];
        $resu = $weixin->http_curl($urls,'get','json');
        return $resu;
    }

    //练习方法   无作用
    public function xiaoshu(){
                $this->display('indexss');
    }

    public function shu(){
        $this->display('3dflower');
    }

    //测试方法  无作用
    public function base(){
       // print_r($_GET);
       echo  C('DB_TYPE');
        echo "aaa";
    }
}