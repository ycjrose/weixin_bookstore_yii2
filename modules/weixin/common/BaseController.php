<?php
namespace app\modules\weixin\common;
use app\common\components\BaseWebController; 
use app\common\services\UrlService;
/**
* 微信模块的共用方法
*/
class BaseController extends BaseWebController {
	public function sendTextMsg($postObj,$content){
		//发送文本消息
		
		$template = "<xml>
		            <ToUserName><![CDATA[%s]]></ToUserName>
		            <FromUserName><![CDATA[%s]]></FromUserName>
		            <CreateTime>%s</CreateTime>
		            <MsgType><![CDATA[text]]></MsgType>
		            <Content><![CDATA[%s]]></Content>
		            </xml>"; 
		$toUser = $postObj->FromUserName;
		$fromUser = $postObj->ToUserName;
		$time = time();
		$send_info = sprintf($template,$toUser,$fromUser,$time,$content);
		return $send_info;     
	}
	public function sendImageMsg($postObj,$imageMsgs = []){
		//发送图文消息
		
		$template = "<xml>
		            <ToUserName><![CDATA[%s]]></ToUserName>
		            <FromUserName><![CDATA[%s]]></FromUserName>
		            <CreateTime>%s</CreateTime>
		            <MsgType><![CDATA[news]]></MsgType>
		            <ArticleCount>".count($imageMsgs)."</ArticleCount>
		            <Articles>";
		foreach ($imageMsgs as $key => $value) {
		$template.= "<item>
		            <Title><![CDATA[".$value['title']."]]></Title> 
		            <Description><![CDATA[".$value['description']."]]></Description>
		            <PicUrl><![CDATA[".$value['picUrl']."]]></PicUrl>
		            <Url><![CDATA[".$value['url']."]]></Url>
		            </item>";   
		}
		$template.= "</Articles>
		             </xml>";           
		$toUser = $postObj->FromUserName;
		$fromUser = $postObj->ToUserName;
		$time = time();
		$send_info = sprintf($template,$toUser,$fromUser,$time);
		return $send_info;
	}
}