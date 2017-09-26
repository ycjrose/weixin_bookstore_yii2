<?php
namespace app\common\services;
/**
* 所有服务的基类
*/
class BaseService{
	protected static $err_msg = '';
	protected static $err_code = '';
	public static function _err($code,$msg){
		self::$err_code = $code;
		self::$err_msg = $msg;
		return false;
	}
	public static function getErrCode(){
		return self::$err_code;
	}
	public static function getErrMsg(){
		return self::$err_msg;
	}
	//array转xml
	public function arrayToXml($arr = []){
		$xml = '<xml>';
		foreach ($arr as $k => $v) {
			if(is_numeric($v)){
				$xml .= '<'.$k.'>'.$v.'</'.$k.'>';
			}else{
				$xml .= '<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
			}
		}
		$xml .= '</xml>';
		return $xml;
	}
	//xml转array
	public function xmlToArray($xml){
		$arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
		return $arr;
	}

}