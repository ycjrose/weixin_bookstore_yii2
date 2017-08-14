<?php
namespace app\modules\web\common;
use app\common\components\BaseWebController; 
use app\models\User;
use app\common\services\UrlService;
/**
* web模块下统一控制器中独有的验证
* 1：指定特定的布局文件 
* 2：进行登录验证
*/
class BaseController extends BaseWebController {

	protected $auth_cookie_name = 'ycj_book';

	public $current_user = null ;//当前登录人信息
	public $allowAllAction = [
		'web/user/login'
	];

	public function __construct($id, $module, $config = []){
	    parent::__construct($id, $module, $config = []);
	    $this->layout = 'main';

	}
	//登录统一验证
	public function beforeAction($action){
		$is_login = $this->checkLoginStatus();
		//有些页面不需要验证
		if(in_array($action->getUniqueId(), $this->allowAllAction)){
			return true;
		}
		if(!$is_login){
			if(\Yii::$app->request->isAjax){
				$this->renderJson(-302,'未登录，请先登录');
			}
			$this->redirect(UrlService::buildWebUrl('/user/login'));
		}
		$view = \Yii::$app->view->params['user_info'] = $this->current_user;
		return true;
	}
	/**
	* 验证当前登录态是否有效返回布尔值
	*/
	protected function checkLoginStatus(){
		$auth_cookie = $this->getCookie($this->auth_cookie_name);
		if(!$auth_cookie){
			return false;
		}
		list($auth_token,$uid) = explode('#', $auth_cookie);
		if(!$auth_token || !$uid){
			return false;
		}
		if(!preg_match('/^\d+$/', $uid)){ 
			return false;
		}
		//取出登录者信息
		$user_info = User::find()->where(['uid' => $uid])->one();
		if(!$user_info){
			return false;
		}
		$auth_token_md5 = $this->getAuthToken($user_info);
		if($auth_token != $auth_token_md5){
			return false;
		}
		//通过所有验证
		$this->current_user = $user_info;
		return true;
	}

	//设置登录态方法
	public function setLoginStatus($user_info){
		$auth = $this->getAuthToken($user_info);
		$this->setCookie($this->auth_cookie_name,$auth.'#'.$user_info['uid'],time()+3600*24*7);
	}
	//删除登录态方法
	public function removeLoginStatus(){
		return $this->removeCookie($this->auth_cookie_name);
	}
	//统一生成加密字段
	public function getAuthToken($user_info){
		return md5($user_info['login_name'].$user_info['login_pwd'].$user_info['login_salt']);
	}
}