<?php

namespace app\modules\weixin\controllers;

use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\common\services\weixin\RequestService;

 
/**
* 微信自定义菜单
*/
class MenuController extends BaseWebController{
 
	public function actionSet(){
		$button_url = \Yii::$app->params['domain'];
		$menu = [
			'button' => [
				[
					'name' => '商城信息',
					'type' => 'view',
					'url' => $button_url.UrlService::buildMUrl('/default/index'),
				],
				[
					'name' => '我的信息',
					'type' => 'view',
					'url' => $button_url.UrlService::buildMUrl('/user/index'),
				],
				[
					'name' => '开发者博客',
					'type' => 'view',
					'url' => 'http://www.ycjblog.top:8086',
				],
			],
		]; 

		$config = \Yii::$app->params['weixin'];
		RequestService::setConfig($config['appid'],$config['token'],$config['sk']);
		$access_token = RequestService::getAccessToken();
		var_dump(RequestService::getErrMsg());
		if($access_token){
			$url = 'menu/create?access_token='.$access_token;
			$res = RequestService::send($url,json_encode($menu,JSON_UNESCAPED_UNICODE),'POST');
			var_dump($res);
		}
	}
}