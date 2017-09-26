<?php
namespace app\common\services\weixin;

use app\common\services\BaseService;
use app\common\components\HttpClient; 
/**
* 处理微信支付接口类
*/

class PayApiService extends BaseService{
	private $params = [];  //需要的请求参数
	private $wxpay_params = []; //公众号端的微信信息
	private $prepay_id = null;
	private $prepay_info = null;

	public function __construct($wxpay_params = []){
		$this->wxpay_params = $wxpay_params;
	}

	public function setWxpayParams($wxpay_params = []){
		$this->wxpay_params = $wxpay_params;
	}

	public function setParams($params = []){
		$this->params = $params;
	}
	//调用微信统一下单接口获取prepay_info
	public function getPrepayInfo(){
		$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
		$this->params['nonce_str'] = $this->createNonceStr();
		$this->params['sign'] = $this->getSign($this->params);
		$xml_data = $this->arrayToXml($this->params);
		$res = HttpClient::post($url,$xml_data);
		if($res){
            $res = $this->xmlToArray($res);
        	if($res['return_code'] && $res['return_code'] == 'SUCCESS'){
        		$this->prepay_info = $res;
        		$this->prepay_id = $this->prepay_info['prepay_id'];
         		return $this->prepay_info;
        	}
        }
        return false;

	}
	//组装jsapi所需的参数
	public function getJsApi(){
		$arr = [
			'appId' => $this->wxpay_params['appid'],
			'timeStamp' => time(),
			'nonceStr' => $this->createNonceStr(),
			'package' => 'prepay_id='.$this->prepay_id,
			'signType' => 'MD5',
		];
		$arr['paySign'] = $this->getSign($arr);
		return $arr;

	}
	//检查微信接口发来的签名是否与签名算法生成的签名一致
	public function checkSign($wx_sign){
		$newSign = $this->getSign($this->params);
		if($newSign == $wx_sign){
			return true;
		}
		return false;
	}
	//生成随机字符串
	private function createNonceStr($length = 32){
		$chars = 'abcdefghijklmnopqrstwvyxyzABCDEFGHIJKLMNOPQRSTWVYXYZ0123456789!@#$%';
		$str = '';
		for($i = 0;$i < $length;$i++){
			$str .= $chars[mt_rand(0,strlen($chars) - 1)];
		}
		return $str;
	}
	//获取签名
	private function getSign($str_array){
		$stringA = $this->formatStringA($str_array,false);
		$stringA = $stringA.'&key='.$this->wxpay_params['pay']['key'];
		$stringA = md5($stringA);
		$sign = strtoupper($stringA);
		return $sign;
	}
	//格式化参数，获取stringA,签名过程需要,中文需要urlencode,urlencode是布尔值
	private function formatStringA($str_array,$urlencode){
		$buff = '';
		ksort($str_array);
		foreach ($str_array as $k => $v) {
			if($urlencode){
				$v = urlencode($v);
			}
			$buff .= $k.'='.$v.'&';
		}
		$stringA = '';
		if(strlen($buff) > 0){
			$stringA = substr($buff, 0, strlen($buff) - 1);
		}
		return $stringA;
	}
}