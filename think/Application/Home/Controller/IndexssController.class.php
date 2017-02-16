<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends Controller{
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
				$content = '欢迎关注';
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
				/* 
				 * <xml>
						<ToUserName><![CDATA[toUser]]></ToUserName>
						<FromUserName><![CDATA[fromUser]]></FromUserName>
						<CreateTime>12345678</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[你好]]></Content>
						</xml> */
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
				$msgType = 'text';
				echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
			//}
		}
		
		
		//用户发送tuwen1关键字的时候，回复一个单图文
		if(strtolower($postObj->MsgType)== 'text' && trim($postObj->Content)== 'tuwen1'){
			$toUser = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
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
			echo sprintf($template,$fromUser,$toUser,time(),'news');
			//注意：进行多图文发送时，子图文个数不能超过10个
			
		}
	}//repomseMsg  end
	//封装curl采集工具（php里非常强大的采集工具，需要掌握）
	 function http_curls(){
		//获取imooc
		//初始化curl
		$ch = curl_init();
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1974c1f0f7d6ca40&secret=900d0e4542b0fd7ca12d5abcc0fda90d';
		//设置curl的参数
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		//采集
		$output = curl_exec($ch);
		//bd.liangmlk/imooc.php/http_curl
		//关闭采集
		curl_close($ch);
		var_dump($output);
	}
	
	/* 
	 *$url 接口url string
	 *$type 请求数据类型 string
	 *$res 返回数据类类型 string
	 *$arr post请求参数 string 
	 *  
	 */
	public function http_curl($url,$type='get',$res='json',$arr =''){
		//初始化curl
		$ch = curl_init();
		//设置curl的参数	
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
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
	
	//微信access_token的获取方法
	/* function getWxAccessToken(){
		//请求url地址
		$appid = 'wx70294fdd2f08bc43';
		$appsecret = 'adde26de34e20936ca47cf0d9c292af5';
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx70294fdd2f08bc43&secret=".$appsecret;
		echo $url;
		//初始化
		$ch = curl_init();
		//设置参数
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		//调用接口
		$res = curl_exec($ch);	
		//关闭curl
		curl_close($ch);
		if(curl_errno($ch)){
			var_dump(curl_error($ch));
		}
		$arr = json_decode($res,true);
		var_dump($arr);
	} */
	//获得微信IP地址的获取方法
	public function getServerIp(){
		//根据access_token获得微信Ip地址
		$accessToken = "";//双引号里填写getWxAccessToken方法获得的access_token值
		$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		//调用接口
		$res = curl_exec($ch);
		//关闭curl
		curl_close($ch);
		if(curl_errno($ch)){
			var_dump(curl_error($ch));
		}
		$arr = json_decode($res,true);
		var_dump($arr);
	}
	
	//微信access_token的获取方法
	public function getWxAccessToken(){
		//将access_token 存在session|cookie中
		if($_SESSION['access_token'] != NULL && $_SESSION['expire_time']>time()){
			//如果access_token存在并且没有过期
			return $_SESSION['access_token'];
		}else {
			//如果access_token不存在或者已过期，重新取access_token
			$appid = 'wx70294fdd2f08bc43';
			$appsecret = 'adde26de34e20936ca47cf0d9c292af5';
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
			$res = $this->http_curl($url,'get','json');
			$access_token = $res['access_token'];
			$_SESSION['access_token'] = $access_token;
			$_SESSION['expire_time'] = time()+7100;
			print_r($res);
			echo "das";
			/* return $access_token; */
		//}
		} 
	}
	
	public function deFinedItem(){
		//创建微信菜单
		//目前微信接口的调用方式都是通过curl post/get
		header('content-type:text/html;charset=UTF-8');
		$access_token = $this->getWxAccessToken();
		$url ='https://api.weixin.qq.com/cgi-bin/menu/create?access_token=FE_mo6pyMknAdZ5WrOnKcHGdc5VPkdn_X-OYmVKquO-kLcM0vSt_D-bE5kI3okV7IgA-U81qtj6kyDnLdCbcrroJ8N92xyjUmA1QIzqeoiQGBNiABACXJ';
		$postArr = array(
				'button'=>array(
						array(
							'name'=>'菜单一',
							'type'=>'click',
							'key'=>'item1'
						),	//第一个一级菜单
						array(
							'name'=>urlencode('菜单二'),
							'sub_button'=>array(
									array(
										'name'=>urlencode('歌曲'),
										'type'=>'click',
										'key'=>'songs'
									),
									array(
										'name'=>urlencode('电影'),
										'type'=>'view',
										'url'=>'http://www.baidu.com'
									),
						)),	//第二个一级菜单
						array(
							'name'=>urlencode('菜单三'),
							'type'=>'view',
							'key'=>'http://www.qq.com'
						) 	//第三个一级菜单
						
			));
		echo $postJson = urldecode(json_encode($postArr));
		$ch = curl_init();
		//设置curl的参数
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postJson);
		$output = curl_exec($ch);
		//关闭采集
		curl_close($ch);
		$res = json_decode($output,true);		
		echo $_SESSION;
		
	}
	
 	public function lianxi(){
		$appid = 'wx1974c1f0f7d6ca40';
		$appsecret = '900d0e4542b0fd7ca12d5abcc0fda90d';
		
		$url = 'http://127.0.0.1/index.php/Index/aaa';
		//$url = 'http://www.baidu.com';
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		
		
		$a = curl_exec($ch);
		var_dump($a);
		print_r($a);
	}
	
	public function aaa(){
		$aa = array(
			'name'=>'张三',
			'pwd'=>123456,
			'住址'=>'北京'	
				
		);
		echo $aa;
	}
	
}//class end






















