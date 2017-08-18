<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;

use app\common\components\HttpClient;

use app\common\services\UrlService;

/**
* 微信授权登录
*/
class OauthController extends BaseController{
	
	public function actionLogin(){
		$scope = $this->get('scope','snsapi_base');
		$appid = \Yii::$app->params['weixin']['appid'];
		$redirect_uri = \Yii::$app->params['domain']['windows'].UrlService::buildMUrl('/oauth/callback');
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
		return $this->redirect($url);
	}
	public function actionCallback(){
		$code = $this->get('code');
		if(!$code){
			return $this->goHome();
		}
		//通过code获取网页授权的access_token
		$appid = \Yii::$app->params['weixin']['appid'];
		$sk = \Yii::$app->params['weixin']['sk'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$sk}&code={$code}&grant_type=authorization_code";
		$res = HttpClient::get($url);
		$res = @json_decode($res,true);
		$res_token = isset($res['access_token']) ? $res['access_token'] : '';
		if(!$res_token){
			return $this->goHome();
		}
		$res_openid = isset($res['openid']) ? $res['openid'] : '';
		$res_scope = isset($res['scope']) ? $res['scope'] : '';
		if($res_scope != 'snsapi_userinfo'){
			return $this->goHome();
		}
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$res_token}&openid={$res_openid}&lang=zh_CN";
		$weixin_user_info = HttpClient::get($url);
		
	}
}