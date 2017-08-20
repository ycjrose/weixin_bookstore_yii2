<?php

namespace app\modules\weixin\controllers;

use app\modules\weixin\common\BaseController;

use app\common\components\HttpClient;

class MsgController extends BaseController{

    public function actionIndex()
    {  

        if($this->checkSignature() && $_GET['echostr']){
            //用于微信第一次认证
        	return $_GET['echostr'];
        }else{

            return $this->SendMsg();
        }
           
       
    }
    //接受时间推送并回复
    public function SendMsg(){
        $postStr = file_get_contents('php://input');
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // <xml>
        // <ToUserName><![CDATA[toUser]]></ToUserName>
        // <FromUserName><![CDATA[FromUser]]></FromUserName>
        // <CreateTime>123456789</CreateTime>
        // <MsgType><![CDATA[event]]></MsgType>
        // <Event><![CDATA[subscribe]]></Event>
        // </xml>
        //如果是订阅事件
        if(strtolower($postObj->MsgType) == 'event'){
            //关注事件
            if(strtolower($postObj->Event) == 'subscribe'){
                //回复消息
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $content = '欢迎关注ycj的公众号！';
                $send_info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
                
                return $send_info;
            }
        }
        //如果是用户发来的消息
        if(strtolower($postObj->MsgType) == 'text'){
            $vipSay = trim($postObj->Content);
            if($vipSay == '推荐'){
                //发送图文消息
               $imageMsgs = [
                   [
                       'title' => 'ycj的博客',
                       'description' => '个人的记录与总结',
                       'picUrl' => 'http://47.93.59.20/upload/2017/08/03/5982aaaec2be5.jpg',
                       'url' => 'http://47.93.59.20/',
                   ],
                   [
                       'title' => '百度',
                       'description' => 'baidu',
                       'picUrl' => 'http://47.93.59.20/upload/2017/08/03/5982aaaec2be5.jpg',
                       'url' => 'http://www.baidu.com',
                   ],
                   [
                       'title' => 'qq',
                       'description' => 'qq',
                       'picUrl' => 'http://47.93.59.20/upload/2017/08/03/5982aaaec2be5.jpg',
                       'url' => 'http://www.qq.com',
                   ],
               ];
               return $this->sendImageMsg($postObj,$imageMsgs);

            }else{
                //发送文本消息
                if(strpos( $vipSay,'天气' ) !== false){
                    //调用第三方天气接口
                    $data = 'theCityCode='.mb_substr( $vipSay,0,mb_strlen($vipSay) - 2).'&theUserID=';
                    HttpClient::setHeader([
                        'application/x-www-form-urlencoded;charset=utf-8',
                        'Content-Length:'.strlen($data),
                    ]);
                    $url = 'ws.webxml.com.cn/WebServices/WeatherWS.asmx/getWeather';
                    $res = HttpClient::post($url,$data);
                    $resArr = (array)simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $content = $resArr['string'][0]."\n".$resArr['string'][3]."\n".$resArr['string'][4]."\n".$resArr['string'][5];

                }else{
                    switch ($vipSay) {
                        case '客服电话':
                            $content = '客服ycj:13255287051';
                            break;
                        case '博客':
                            $content = '<a href="http://47.93.59.20/">点击博客地址</a>';
                            break;
                        default:
                            $content = '请输入正确的内容';
                            break;
                    }
                }
                
                return $this->sendTextMsg($postObj,$content);
            }

            
        }
        

       
    }
    public function checkSignature(){
    	$signature = trim($this->get('signature'));
    	$timestamp = trim($this->get('timestamp'));
    	$nonce = trim($this->get('nonce'));
    	$tmpArr = array(\Yii::$app->params['weixin']['token'],$timestamp,$nonce);
    	sort($tmpArr);
    	$tmpStr = implode($tmpArr);
    	$tmpStr = sha1($tmpStr);

    	if($tmpStr == $signature){
    		return true;
    	}else{
    		return false;
    	}
    }
}
