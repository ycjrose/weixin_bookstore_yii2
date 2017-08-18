<?php
namespace app\controllers;
use app\common\components\BaseWebController;
use app\common\services\UtilService;
use app\common\services\captcha\ValidateCode;
use app\models\sms\SmsCaptcha;
/**
* 默认页面控制类
*/
class DefaultController extends BaseWebController{

	private $captcha_cookie_name = 'ValidateCode'; 

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
		$model_sms->buildSmsCaptcha($mobile,UtilService::getIP());
		if($model_sms){
			//接入发短信的接口
			return $this->renderJson(200,'发送成功'.$model_sms->captcha);
		}
		return $this->renderJson(-1,'系统出错');
	}
	//验证绑定是否成功
	public function actionLogin(){
		$all_post = [
			'mobile' => trim($this->post('mobile')),
			'img_captcha' => trim($this->post('img_captcha')),
			'captcha_code' => trim($this->post('captcha_code')),
		];
		//验证图形验证码是否正确
		$img_captcha = $this->getCookie($this->captcha_cookie_name);
		if(strtolower($all_post['img_captcha']) != $img_captcha){
			return $this->renderJson(-1,'图形验证码不正确,请刷新验证码重试');
		}
		//手机验证码是否正确
		$model_sms = new SmsCaptcha();
		if(!$model_sms->checkSmsCaptcha($all_post['mobile'],$all_post['captcha_code'])){
			return $this->renderJson(-1,'手机验证码错误');
		}
		$this->removeCookie($this->captcha_cookie_name);
		return $this->renderJson(200,'登陆成功');
	}

}