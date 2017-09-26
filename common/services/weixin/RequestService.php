<?php
namespace app\common\services\weixin;

use app\common\services\BaseService;

use app\common\components\HttpClient; 

use app\models\member\OauthAccessToken;
/**
* 处理微信请求的类
*/

class RequestService extends BaseService{

	private static $app_token = '';
	private static $app_id = '';
	private static $app_secret = '';
	//请求的url前缀
	private static $url = 'https://api.weixin.qq.com/cgi-bin/';

	//获取微信的access_token,每次调用微信的接口都需要该验证
	public static function getAccessToken(){
		date_default_timezone_set('PRC');
		$date_now = date('Y-m-d H:i:s');
		$access_token_info = OauthAccessToken::find()->where(['>','expired_time',$date_now])->limit(1)->one();
		if($access_token_info){
			return $access_token_info['access_token'];
		}

		//调取接口获取
		$path = 'token?grant_type=client_credential&appid='.self::getAppId().'&secret='.self::getAppSecret();
		$res = self::send($path);
		if(!$res){
			return self::_err(-1,self::getErrMsg());
		}

		//保存从微信获取的token到数据库
		$model_access_token = new OauthAccessToken();
		$all_back = [
			'access_token' => $res['access_token'],
			'expired_time' => date('Y-m-d H:i:s',$res['expires_in']+time()-200 ),
			'created_time' => $date_now,
		];
		$model_access_token->setAttributes($all_back);
		$model_access_token->save(0);
		return $res['access_token'];
	}
	//向微信发起请求
	public static function send($path,$data=[],$method = 'GET'){
		$request_url = self::$url.$path;
		if($method == 'POST'){
			$res = HttpClient::post($request_url,$data);
		}else{
			$res = HttpClient::get($request_url,[]);
		}

		$resData = @json_decode($res,true);//true代表转换成关联数组
		if(!$resData || (isset($resData['errcode']) && $resData['errcode']) ){
			return self::_err(-1,$resData['errmsg']);
		}

		return $resData;

	}
	public static function setConfig($app_id,$app_token,$app_secret){
		self::$app_id = $app_id;
		self::$app_token = $app_token;
		self::$app_secret = $app_secret;
	}

	public static function getAppToken(){
		return self::$app_token;
	}

	public static function getAppId(){
		return self::$app_id;
	}

	public static function getAppSecret(){
		return self::$app_secret;
	}
}