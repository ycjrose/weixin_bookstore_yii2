<?php
/** 短信验证码类
* 引入阿里云短信验证服务
*/

namespace app\common\services\captcha;
use app\common\services\BaseService;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Regions\EndpointConfig;

class SmsCodeService extends BaseService{

	public static function sendSmsCode($member_phone,$code){
		$a = $code;
		$accessKeyId = "LTAIyadv595ZFdBj";//参考本文档步骤2
		$accessKeySecret = "SjOWAlDOInARgVPuG2l78vh4jMydtX";//参考本文档步骤2
		//短信API产品名（短信产品名固定，无需修改）
		$product = "Dysmsapi";
		//短信API产品域名（接口地址固定，无需修改）
		$domain = "dysmsapi.aliyuncs.com";
		//暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
		$region = "cn-hangzhou";
		//初始化访问的acsCleint
		$profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
		// 手动加载endpoint
		EndpointConfig::load();
		DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
		$acsClient= new DefaultAcsClient($profile);
		$request = new SendSmsRequest;
		//必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
		$request->setPhoneNumbers($member_phone);
		//必填-短信签名
		$request->setSignName("ycj的书海");
		//必填-短信模板Code
		$request->setTemplateCode("SMS_100015017");
		//选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
		$request->setTemplateParam("{\"code\":".$a.",\"product\":\"云通信服务\"}");
		//选填-发送短信流水号
		$request->setOutId("1234");
		//发起访问请求
		$acsResponse = $acsClient->getAcsResponse($request);
		if($acsResponse->Code == 'OK'){
			return true;
		}else{
			return self::_err(-1,$acsResponse->Code);
		}
	}

}
