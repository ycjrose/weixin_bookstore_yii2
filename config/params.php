<?php

return [
    'title' => 'ycj的图书商城',
    'adminEmail' => 'admin@example.com',
    'upload' => [
    	'avatar' => '/uploads/avatar',
    	'brand' => '/uploads/brand',
    	'book' => '/uploads/book',
    ],
    //'domain' => 'http://ycjbook.tunnel.echomod.cn',
    'domain' => 'http://www.ycjblog.top',

    'tuling_key' => '33f7d08d488b44868b0f03a6eee16191',
        
    'weixin' => [
    	'appid' => 'wxc1d2aa71e4cd80dd',
    	'sk' => 'd380974aa0bde259c9c8af972ff9988b',
    	'token' => 'ycjawlx',
        'aeskey' => 'g4d0oLHfXUzc4FH1l3PmBFxJWOnSwgUOWT7fF20gD6K',
        'pay' => [
            'key' => '',
            'mch_id' => '',
            'notify_url' => [
                'm' => '/pay/callback',
            ],
        ],
    ],
];
