<?php
namespace app\controllers;
use app\common\components\BaseWebController;
use app\common\services\UtilService;
use app\common\services\AreaService;
use app\common\services\captcha\ValidateCode;
use app\models\sms\SmsCaptcha;
use app\common\services\captcha\SmsCodeService;
use Da\QrCode\QrCode;
/**
* 默认页面控制类
*/
class DefaultController extends BaseWebController{

	private  $captcha_cookie_name = 'ValidateCode'; 

	public function actionIndex(){
		
		return $this->render('index');
	}
	//生成图形验证码
	public function actionImg_captcha(){
		$font_path = UtilService::getRootPath().'/web/fonts/captcha.ttf';
		$captcha_handle = new ValidateCode($font_path);
		$captcha_handle->doimg();
		$this->setCookie($this->captcha_cookie_name,$captcha_handle->getCode());
		
	}
	//发送手机验证码
	public function actionGet_captcha(){
		
		$mobile = trim($this->post('mobile'));
		if(!$mobile || !preg_match('/^1[0-9]{10}$/',$mobile) ){
			return $this->renderJson(-1,'请输入正确的手机号');
		}
		//一分钟内不能重复发送
		$info = SmsCaptcha::find()->where(['mobile' => $mobile])->orderBy(['id' => SORT_DESC])->asArray()->one();
	
		if($info && strtotime($info['created_time']) > time()-60){
			return $this->renderJson(-1,'请过一分钟后重试！');
		}
		$model_sms = new SmsCaptcha();
		//先生成验证码存入数据库
		$res1 = $model_sms->buildSmsCaptcha($mobile,UtilService::getIP());
		if(!$res1){
			return $this->renderJson(-1,'系统出错');
		}
		//接入发短信的接口，向用户发短信
		$res2 = SmsCodeService::sendSmsCode($mobile,$model_sms->captcha);
		if(!$res2){
			return $this->renderJson(-1,SmsCodeService::getErrMsg());
		}
		
		return $this->renderJson(200,'发送成功');
	
		
	}
	//根据省份的id取出市和区的函数
	public function actionCascade(){
		$id = intval($this->get('id',0));
		$tree_info = AreaService::getCityTree($id);
		return $this->renderJson(200,'成功',$tree_info);
	}
	//展示二维码
	public function actionQrcode(){
		$qrcode_url = $this->get('qrcode_url','');
		$qrCode = (new QrCode($qrcode_url))
		    ->setSize(250)
		    ->setMargin(5);
		// $qrCode->writeFile(__DIR__ . '/code.png');
		header('Content-Type: '.$qrCode->getContentType());
		echo $qrCode->writeString();
		exit;
	}

}