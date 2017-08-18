<?php
namespace app\modules\m\common;
use app\common\components\BaseWebController; 
use app\common\services\UrlService;
/**
* m模块下统一控制器中独有的验证
* 1：指定特定的布局文件 
* 2：进行登录验证
*/
class BaseController extends BaseWebController {

	public function __construct($id, $module, $config = []){
	    parent::__construct($id, $module, $config = []);
	    $this->layout = 'main';

	}
	//登录统一验证
	public function beforeAction($action){
	
		return true;
	}
	//跳回首页的方法
	public function goHome(){
		return $this->redirect(UrlService::buildMUrl('/default'));
	}
}