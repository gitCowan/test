<?php
require 'autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
// 用于签名的公钥和私钥
$accessKey = '-JLvYg-7Pa1SGcLzw1qyrzSt5dqpp-7Xr5ZB_XI3';
$secretKey = 'p0srnaa2qyYM7fS5kKp_OCUTYwiUMb-Jr8C9VXxY';

// 初始化签权对象
$auth = new Auth($accessKey, $secretKey);
$bucket = 'show';

//生成上传token
$token = $auth->uploadToken($bucket);

//初始化Auth状态：
$auth = new Auth($accessKey, $secretKey);

//初始化BucketManager
$bucketMgr = new BucketManager($auth);

//你要测试的空间， 并且这个key在你空间中存在
$key = '1608181471509129560';

//删除$bucket 中的文件 $key
$err = $bucketMgr->delete($bucket, $key);
