<?php
namespace Home\Model;
use Think\Model;

class TokenModel extends Model
{
	private $_db = '';
	public function __construct()
	{
		$this->_db = M("token");
	}

	public function aa(){
		return "aaa";
	}

	public function getLogin($nickname = ''){
		$arr = array(
			'nickname'=>$nickname
		);
			$data = $this->_db->where($arr)->find();
		return $data;
	}

}