<?php
return array(
	//'配置项'=>'配置值'
    /*Redis设置*/
    'DATA_CACHE_TYPE'       =>  'redis',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'REDIS_HOST'            => '127.0.0.1', //主机
    'REDIS_PORT'            => '6379', //端口
    'REDIS_CTYPE'           => 1, //连接类型 1:普通连接 2:长连接
    'REDIS_TIMEOUT'         => 0, //连接超时时间(S) 0:永不超时 // 子目录缓存级别
);