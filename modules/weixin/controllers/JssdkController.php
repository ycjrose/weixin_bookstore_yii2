<?php

namespace app\modules\weixin\controllers;

use app\common\components\BaseWebController;

use app\common\services\weixin\RequestService;

/**
* 引入微信jssdk接口的类
*/
class JssdkController extends BaseWebController{
	public function actionIndex(){
		$ticket = $this->getJsapiTicket();
		//var_dump($ticket);
		$noncestr = $this->createNonceStr();
		$timestamp = time();
		$url = $this->get('url');
		$string = "jsapi_ticket={$ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string);
		//var_dump($signature);
		$appid = \Yii::$app->params['weixin']['appid'];
		$data = [
			'appId' => $appid,
			'timestamp' => $timestamp,
			'nonceStr' => $noncestr,
			'signature' => $signature,
		];
		return $this->renderJson(200,'ok',$data);

	}
	//获取jsapi_ticket
	private function getJsapiTicket(){
		//优先从缓存中获取
		$cache_key = 'wx_jsticket';
		$cache = \Yii::$app->cache;
		$ticket = $cache->get($cache_key);
		if(!$ticket){
			//向微信发出请求
			$config = \Yii::$app->params['weixin'];
			RequestService::setConfig($config['appid'],$config['token'],$config['sk']);
			$access_token = RequestService::getAccessToken();
			$res = RequestService::send("ticket/getticket?access_token={$access_token}&type=jsapi");
			//var_dump($res);
			if(isset($res['errcode']) && $res['errcode'] == 0){
				$cache->set($cache_key,$res['ticket'],$res['expires_in'] - 200);
				$ticket =  $res['ticket'];
			}
		}
		return $ticket;
	}
	//生成随机字符串
	private function createNonceStr($length = 16){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$str = '';
		for ($i=0; $i < $length; $i++) { 
		    $str .= $chars[mt_rand(0,strlen($chars)-1)]; 
		}
		return $str;
	}
}