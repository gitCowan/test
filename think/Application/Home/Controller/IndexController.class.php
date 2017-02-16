<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends Controller{
	public function index(){
		echo 'host.com'.U('Index/user',array('id'=>2),'html',false,'host.com');
	}
	public function user(){
		echo $_GET['id'];
	}
	public function setRedis()
	{
		$result = S('names','王朝正s',array('expire' => 10));
		var_dump($result);
	}
	public function getRedis()
	{
		$data = S('name');
		var_dump($data);
	}
}