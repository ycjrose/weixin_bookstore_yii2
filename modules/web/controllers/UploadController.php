<?php
namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;

use app\common\services\UploadService;
/**
* 控制上传的控制器
*/
class UploadController extends BaseController{
	/**
	* 上传借口
	* bucket: avatar头像图片/brand品牌/book图书
	*/
	//允许上传的类型
	private $allow_file_type = ['jpg','png','jpeg','gif'];

	public function actionPic(){
		$bucket = trim($this->post('bucket'));
		$callback = 'window.parent.upload';//返回给父页面的js
		if(!$_FILES || !isset($_FILES['pic'])){
			return "<script>{$callback}.error('请选择文件之后再提交');</script>";
		}

		$file_name = $_FILES['pic']['name'];
		$file_type = strtolower(end(explode('.', $file_name)));

		if(!in_array($file_type, $this->allow_file_type)){
			return "<script>{$callback}.error('只允许类型jpg,jpeg,gif,png');</script>";
		}

		//上传图片业务逻辑 todo
		$res = UploadService::uploadByFile($file_name,$_FILES['pic']['tmp_name'],$bucket);
		if(!$res){
			$msg = UploadService::getErrMsg();
			return "<script>{$callback}.error('{$msg}');</script>";
		}
		return "<script>{$callback}.success('{$res['path']}');</script>";
	}
}