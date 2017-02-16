<?php
namespace Home\Controller;
use Think\Controller;
require './jiguang/autoload.php';
use JPush\Client as JPush;

class JiguangController extends Controller{

	public function note($tel = '18336413604',$content = '报名123456成功，请保持电话畅通。【爱上秀】'){


		function Post($data, $target) {
			$url_info = parse_url($target);
			$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
			$httpheader .= "Host:" . $url_info['host'] . "\r\n";
			$httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
			$httpheader .= "Content-Length:" . strlen($data) . "\r\n";
			$httpheader .= "Connection:close\r\n\r\n";
			//$httpheader .= "Connection:Keep-Alive\r\n\r\n";
			$httpheader .= $data;

			$fd = fsockopen($url_info['host'], 80);
			fwrite($fd, $httpheader);
			$gets = "";
			while(!feof($fd)) {
				$gets .= fread($fd, 128);
			}
			fclose($fd);
			return $gets;
		}
		$target = "http://api.chanzor.com/send";
		$password = "q6tlcbjf4x";
		$pwd = strtoupper(md5($password));
		//替换成自己的测试账号,参数顺序和wenservice对应
		$post_data = "action=send&userid=&account=989b71&password=".$pwd."&mobile=".$tel."&sendTime=&content=".$content;

		//$binarydata = pack("A", $post_data);
		$gets = Post($post_data, $target);
		$result = json_decode(substr($gets, 161));
		function object_array($array)
		{
			if(is_object($array))
			{
				$array = (array)$array;
			}
			if(is_array($array))
			{
				foreach($array as $key=>$value)
				{
					$array[$key] = object_array($value);
				}
			}
			return $array;
		}

		$array = object_array($result);
		print_r($array);

	}

	/*public function index(){
		//$client = new \JPush\Client($app_key, $master_secret);
		$push = new JPush($app_key, $master_secret);
		$push ->push();
		$push ->setPlatform('all');
		$push ->addAllAudience("tag1");
		$push ->setNotificationAlert('alert');
		$push ->setNotificationAlert('Hello, JPush');
		$push ->send();

		$push->addWinPhoneNotification($alert=null, $title=null, $_open_page=null, $extras=null);

		$push->message('Hello JPush', [
			'title' => 'Hello',
			'content_type' => 'text',
			'extras' => [
				'key' => 'value'
			]
		]);
	}*/
}