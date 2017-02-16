<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/19 0019
 * Time: 上午 10:58
 */
namespace Home\Controller;
use Think\Controller;


class WeixinController extends Controller{
    public function _construct(){
    }
    public function index(){
        //获得参数 signature nonce token timestamp echostr
        $nonce = $_GET["nonce"];
        $token = "weixin";
        $timestamp = $_GET["timestamp"];
        $echostr = $_GET["echostr"];
        $signature = $_GET["signature"];
        //形成数组，然后按字典排序
        $array = array();
        $array = array($nonce,$timestamp,$token);
        sort($array);
        //拼接成字符串，sha1加密，然乎与signsture进行比对
        $str = sha1(implode($array));
        if($str == $signature && $echostr){
            //第一次接入weixin api接口的时候
            echo $echostr;
            exit;
        }else{
            $this->repomseMsg();

        }
    }
    //接受事件推送并回复
    public function repomseMsg(){
        //获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //处理消息类型，并设置回复类型和内容
        /* 推送XML数据包格式
         * <xml>
            开发者账号<ToUserName><![CDATA[toUser]]></ToUserName>
            发送方账号<FromUserName><![CDATA[FromUser]]></FromUserName>
            消息创建时间<CreateTime>123456789</CreateTime>
            消息类型<MsgType><![CDATA[event]]></MsgType>
            事件类型<Event><![CDATA[subscribe]]></Event>
            </xml> */
        $postObj = simplexml_load_string($postArr);
        //$postObj->ToUserName = "";
        //$postObj->FromUserName = "";
        //$postObj->CreateTime = "";
        //$postObj->ToUserName = "";
        //$postObj->Event = "";
        //通过该数据包判断是否是订阅的事件推送
        if(strtolower($postObj->MsgType) == "event"){
            //如果是关注 subscribe 事件
            if(strtolower($postObj->Event == 'subscribe')){
                //回复用户消息
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $content = '欢迎关注！';
                $template = "
						<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>
						";
                $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
                echo $info;
                /* <xml>
                   <ToUserName><![CDATA[toUser]]></ToUserName>
                   <FromUserName><![CDATA[fromUser]]></FromUserName>
                   <CreateTime>12345678</CreateTime>
                   <MsgType><![CDATA[text]]></MsgType>
                   <Content><![CDATA[你好]]></Content>
                   </xml>
                 */
            }
        }


        //纯文本回复
      if(strtolower($postObj->MsgType) == 'text'){
            //if判断条件比较单一
            //if($postObj->Content == 'imooc'){
            //switch多条件判断
            switch ($postObj->Content){
                case 1:
                    $content = '你输入的1';
                    break;
                case 2:
                    $content = '你输入的2';
                    break;
                case 3:
                    $content = '你输入的3';
                    break;
                case 4:
                    $content = '请点击'."<a href='http://chaozheng.top'>链接</a>";
                    break;
                case '英文';
                    $content = 'imooc is ok';
                    break;
                case '查看类型';
                    $content = $postObj->MsgType;
                    break;
                case '我输入的内容';
                    $content = $postObj->Content;
                    break;
                case '开发者名字';
                    $content = $postObj->FromUserName;
                    break;
                case '调试者名字';
                    $content = $postObj->ToUserName;
                    break;
                case 'IP';
                    $content = $this->getServerIp();
                    break;
                case 'token';
                    $content = $this->getWxAccessToken();
                    break;
                case 'session';
                    $content = $_SESSION['access_token'];
                    break;
                case 'menu';
                    $content = $this->getMenu();
                    break;
                case "地址查询";
                    $content = $this->getSite();
                    break;
              case "炫彩";
                  $content = '<a href="http://wcz.ittun.com/think/index.php/Accredit/xiaoshu"> 小树 </a>';
                  break;
                case "落叶";
                    $content = '<a href="http://wcz.ittun.com/think/index.php/Accredit/shu"> 落叶 </a>';
                    break;
                case '我要授权';
//                    //$content = '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FAccredit%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect">点我</a>';
                    $content = '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FAccredit%2Fifnull&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect">点我</a>';
                    break;
//                case $postObj->Content;
//                  $content = $postObj->MsgType;
//                  break;
                case $postObj->Content;
                    $content = $postObj->Content;
                    break;
            }
            $template = "
						<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>
						";
            $fromUser = $postObj->ToUserName;
            $toUser = $postObj->FromUserName;
            $time = time();
            //$content = 'imooc is very';
           //text格式  $postObj->MsgType OR $msgType = 'text'
            $msgType = $postObj->MsgType;
            echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
            //}
        }else if($postObj->MsgType != "text"){
          $toUser = $postObj->FromUserName;
          $fromUser = $postObj->ToUserName;
//          $arr = array(
//              array(
//                  'title'=>'imooc',
//                  'description'=>"imooc is very cool",
//                  'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
//                  'url'=>'http://www.imooc.com'
//              )
//          );
//          $template = "<xml>
//                         <ToUserName><![CDATA[%s]]></ToUserName>
//                         <FromUserName><![CDATA[%s]]></FromUserName>
//                         <CreateTime>%s</CreateTime>
//                         <MsgType><![CDATA[%s]]></MsgType>
//
//                         <PicUrl><![CDATA[%s]]></PicUrl>
//                         <MediaId><![CDATA[%s]]></MediaId>
//                         <MsgId>%s</MsgId>
//                         </xml>";
          $fromUser = $postObj->ToUserName;
          $toUser = $postObj->FromUserName;
          $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                        </xml>";
          echo sprintf($template,$toUser,$fromUser,time(),'image');

      }



        //用户发送tuwen1关键字的时候，回复一个单图文
        if(strtolower($postObj->MsgType)== 'text' && trim($postObj->Content)== 'tuwen1'){
            $toUser = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            //图文消息个数，限制为10条以内
            $arr = array(
                array(
                    'title'=>'imooc',
                    'description'=>"imooc is very cool",
                    'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                    'url'=>'http://www.imooc.com'
                )
            );
            $template = "
						<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($arr)."</ArticleCount>
						<Articles>";
            foreach($arr as $k=>$v){
                $template .="<item>
						<Title><![CDATA[".$v['title']."]]></Title>
						<Description><![CDATA[".$v['description']."]]></Description>
						<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
						<Url><![CDATA[".$v['url']."]]></Url>
						</item>";
            }
            $template .="</Articles>
						</xml>";
            echo sprintf($template,$toUser,$fromUser,time(),'news');
            //注意：进行多图文发送时，子图文个数不能超过10个
        }
    }
    //repomseMsg  end

    /*
     * $url 接口url string
     *$type 请求数据类型 string
     *$res 返回数据类类型 string
     *$arr post请求参数 string
     */
    //封装curl采集工具（php里非常强大的采集工具，需要掌握）
    //优化版CURL抓取工具
    public function http_curl($url,$type='get',$res='json',$arr ='',$header=""){
        //初始化curl
        $ch = curl_init();
        //设置curl的参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if( $header != NULL){
            curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        }
        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }
        //采集
        $output = curl_exec($ch);
        //bd.liangmlk/imooc.php/http_curl
        //关闭采集
        curl_close($ch);
        if($res == 'json'){
            if(curl_errno($ch)){
                return curl_errno($ch);
            }else{
                return json_decode($output,true);
            }

        }
    }

    public function getPosition(){


    }

    //获得微信IP地址的获取方法
    public function getServerIp(){
        //根据access_token获得微信Ip地址
        $accessToken = $this->getWxAccessToken();//双引号里填写getWxAccessToken方法获得的access_token值
        echo $accessToken;
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
        $res = $this->http_curl($url,'get','json');
        return $res['ip_list'][0];
    }

    //微信access_token的获取方法
    public function getWxAccessToken(){
        //将access_token 存在session|cookie中
        if($_SESSION['access_token'] != NULL && $_SESSION['expire_time']>time()){
            //如果access_token存在并且没有过期
            return $_SESSION['access_token'];
        }else {
            //如果access_token不存在或者已过期，重新取access_token
            //公众号
//            $appid = 'wx70294fdd2f08bc43';
//            $appsecret = 'adde26de34e20936ca47cf0d9c292af5';
            //测试号
            $appid = 'wx1974c1f0f7d6ca40';
            $appsecret = '900d0e4542b0fd7ca12d5abcc0fda90d';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $res = $this->http_curl($url,'get','json');
            $access_token = $res['access_token'];
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time()+7100;
            return $access_token;
        }
    }

    //测试access_token能否正常获取_结果—正常
/*    public function Token(){
        $appid = 'wx70294fdd2f08bc43';
        $appsecret = 'adde26de34e20936ca47cf0d9c292af5';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        //$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx70294fdd2f08bc43&secret=adde26de34e20936ca47cf0d9c292af5";
        $res = $this->http_curl($url,'get','json');
        return $res['access_token'];
    }*/

    //测试CURL是否正常_结果—正常
/*    public function Url(){
        $url = "http://wcz.ittun.com/index/bb.php";
        $array = array("id"=>1);
        $res = $this->http_curl($url,'post','json',$array);
        return $res['token'];
    }*/

    //需要企业认证才可以用
    public function deFinedItem(){
        //创建微信菜单
        //目前微信接口的调用方式都是通过curl post/get
        header('content-type:text/html;charset=UTF-8');
        $access_token = $this->getWxAccessToken();
        //$access_token = "x171ZK9HozwThSuZ61FtDKvjW5YaDBGKIPKRmA7w0SEnDK4rZ_YZqRbsKufSDijnTtFwevBvtzd1HLGkn6CNzNG3yOhZhjBvFGflThtCmrzrV6VkpR-cvQ4aCV9iBhFeRWJdAGAWBO";
        $url ='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;

        $postArr = array(
            "button"=>array(
                array(
                    'type'=>'click',
                    'name'=>urlencode('嘿嘿'),
                    'key'=>urlencode('V1001_TODAY_MUSIC')
                ),
                array(
                    'type'=>'view',
                    'name'=>urlencode('哈哈'),
                    'url'=>urlencode('http://www.baidu.com')
                ),
                array(
                    'type'=>'click',
                    'name'=>urlencode('呵呵'),
                    'key'=>3
                ),
            )
        );
        $postJson = urldecode(json_encode($postArr));
        $this->http_curl($url,'post','json',$postJson);
    }

    //获取自定义菜单json数据
    public function getMenu(){
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
        $res = $this->http_curl($url,'get','json');
        return $res;
    }

    //天气预报接口需要给地区编号
    public function getWeather($key = ''){
        $url = 'http://apis.baidu.com/tianyiweather/basicforecast/weatherapi?area=101010100';
        $header = array(
            'apikey: df09658b499f59df3c98f4ad2f2656b7',
        );
        $res = $this->http_curl($url,'get','json','',$header);
//        echo "<pre>";
//        print_r($res);
//        echo "</pre>";
        return print_r($res);
    }

    //IP地址查询
    public function getSite($key = ''){
        $url = 'http://apis.baidu.com/apistore/iplookup/iplookup_paid?ip=123.57.245.247';
        $header = array(
            'apikey: df09658b499f59df3c98f4ad2f2656b7',
        );
        $res = $this->http_curl($url,'get','json','',$header);
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        $data = $res['retData']['country'].$res['retData']['province'].$res['retData']['city'].$res['retData']['district'].$res['retData']['carrier'];
        return $data;
    }

    //获取用户code
    public function code(){
       /* $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FWeixin%2Fopenid&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header("Location: $url");*/
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FAccredit%2Fifnull&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header("Location: $url");
    }
    //获取用户openid
    public function openid()
    {
        if($_GET['code'] != NULL){
            $code = $_GET['code'];
            $appid = "wx1974c1f0f7d6ca40";
            $secret = "900d0e4542b0fd7ca12d5abcc0fda90d";
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
            $res = $this->http_curl($url, 'get', 'json');
            //刷新access_token
            $urls = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $res['refresh_token'];
            $resu = $this->http_curl($urls, 'get', 'json');
            $url = 'http://wcz.ittun.com/think/index.php/Accredit/ifnull/openid/'.$resu['openid'];
            header("Location: $url");
        }else{
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FWeixin%2Fopenid&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
            header("Location: $url");
        }
    }
}