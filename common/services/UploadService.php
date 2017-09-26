<?php
namespace app\common\services;
use app\common\services\BaseService;
use app\common\services\UtilService;
use app\models\book\Images;
/**
* 上传服务
*/
class UploadService extends BaseService
{
	//根据文件路径来上传
	public static function uploadByFile($file_name,$file_path,$bucket = ''){
		if(!$file_name){
			return self::_err(-1,'文件名是必须的');
		}
		if(!$file_path || !file_exists($file_path)){
			return self::_err(-1,'请输入合法的文件路径');
		}

		$upload_config = \Yii::$app->params['upload'];
		if(!isset($upload_config[$bucket])){
			return self::_err(-1,'存放的位置出错');
		}

		//保存每个图片对应的秘钥
		$file_type = strtolower(end(explode('.', $file_name)));
		$hash_key = md5(file_get_contents($file_path));
		//每个篮子下面按照日期存放图片
		$upload_dir_path = UtilService::getRootPath().'/web'.$upload_config[$bucket].'/';
		$time_folder = date('Ymd');
		$upload_dir = $upload_dir_path.$time_folder;
		//目录不存在就创建
		if(!file_exists($upload_dir)){
			mkdir($upload_dir,0777);
			chmod($upload_dir,0777);
		}

		$upload_full_name = $time_folder.'/'.$hash_key.".{$file_type}";

		if(is_uploaded_file($file_path)){
			move_uploaded_file($file_path, $upload_dir_path.$upload_full_name);
		}else{
			file_put_contents($upload_dir_path.$upload_full_name, file_get_contents($file_path));
		}
		if($bucket == 'book'){
			date_default_timezone_set('PRC');
			$images = new Images();
			$images->file_key = $upload_full_name;
			$images->created_time = date('Y-m-d H:i:s');
			$images->save(0); 
		}
		
		 
		return [
			'code' => 200,
			'path' => $upload_full_name,
			'prefix' => $upload_config[$bucket].'/',
		];
	}
}