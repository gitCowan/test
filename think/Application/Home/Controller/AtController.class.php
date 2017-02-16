<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/13 0013
 * Time: 下午 4:57
 */
namespace Home\Controller;
use Think\Controller;
use Home\Controller\WxController;


class AtController extends Controller
{
    //以下session
    public function index()
    {
        $weixin = new WxController();
        $code = $_GET['code'];
        $_SESSION['code'] = $code;
        $appid = "wx1974c1f0f7d6ca40";
        $secret = "900d0e4542b0fd7ca12d5abcc0fda90d";
        if ($_SESSION['code'] != NULL) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $_SESSION['code'] . "&grant_type=authorization_code";
        } else {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        }
        $res = $weixin->http_curl($url, 'get', 'json');
        //print_r($res);

        //刷新access_token
        $_SESSION['refresh_token'] = $res['refresh_token'];
        if ($_SESSION['refresh_token'] != NULL) {
            $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $_SESSION['refresh_token'];
        } else {
            $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $res['refresh_token'];
        }
        $resu = $weixin->http_curl($urls, 'get', 'json');
        //print_r($resu);


        //获取用户资料
        $_SESSION['access_token'] = $res['access_token'];
        $_SESSION['openid'] = $resu['openid'];
        if ($_SESSION['access_token'] != NULL && $_SESSION['openid'] != NULL) {
            $urlss = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $_SESSION['access_token'] . "&openid=" . $_SESSION['openid'] . "&lang=zh_CN";
        } else {
            $urlss = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $res['access_token'] . "&openid=" . $resu['openid'] . "&lang=zh_CN";
        }
        $resul = $weixin->http_curl($urlss, 'get', 'json');

    }
}