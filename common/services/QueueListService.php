<?php
namespace app\common\services;
use app\common\services\BaseService;
/**
* 消息队列服务
*/
class QueueListService extends BaseService{
	//发送消息队列
	public static function addQueue($queue_name,$data = []){
		//用redis做消息队列
		try{
			$val = implode(',',$data);
			$redis = new \Redis();
			$redis->connect('127.0.0.1',7200);
			$redis_name = $queue_name;
			$redis_data = $val;
			$redis->lPush($queue_name,$val);
		}catch(Exception $e){
			return self::_err(-1,$e->getMessage());
		}
		return true;
	}
}