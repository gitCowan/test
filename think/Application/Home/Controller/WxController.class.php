<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/19 0019
 * Time: 上午 10:58
 */
namespace Home\Controller;
use Think\Controller;


class WxController extends Controller{
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
                case '我要授权';
                    $content = '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1974c1f0f7d6ca40&redirect_uri=http%3A%2F%2Fwcz.ittun.com%2Fthink%2Findex.php%2FAccredit%2Fifnull&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect">点我</a>';
                    break;
                case $postObj->Content;
                  $content = $postObj->MsgType;
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
            return $_SESSION['access_token'];
        }else {
            //公众号
//            $appid = '';
//            $appsecret = '';
            //测试号
            $appid = '';
            $appsecret = '';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $res = $this->http_curl($url,'get','json');
            $access_token = $res['access_token'];
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time()+7100;
            return $access_token;
        }
    }

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

}