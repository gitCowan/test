<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3 0003
 * Time: 上午 9:31
 */
/*
 * 公用的方法
 * */
function show($status, $message, $data = ''){
    $result = array(
        'status'=>$status,
        'message'=>$message,
        'data'=>$data
    );
    exit(json_encode($result));
}