<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\PayOrderService;
use app\common\services\weixin\PayApiService;
use app\models\Pay\PayOrder;
use yii\log\FileTarget;
use app\models\member\OauthMemberBind;

class PayController extends BaseController{

    public function actionBuy(){
    	//购买支付页面
    	$pay_order_id = intval($this->get('pay_order_id'));
    	$reback_url = UrlService::buildMUrl('/user/index');
    	if(!$pay_order_id){
    		$this->redirect($reback_url);
    	}
    	$order_info = PayOrder::find()->where([
    		'id' => $pay_order_id,
    		'status' => -8,
    		'member_id' => $this->current_user['id']
    	])->one();
    	if(!$order_info){
    		$this->redirect($reback_url);
    	}

        return $this->render('buy',['order_info' => $order_info]);
    }
    public function actionPrepare(){
        //支付核心逻辑
    	$order_id = intval($this->post('order_id'));
        if(!$order_id){
            return $this->renderJson(-1,'系统繁忙');
        }
        //判断是否在微信端
        if(!UtilService::isWechat()){
            return $this->renderJson(-1,'请在微信上完成支付');
        }
        //判断订单合法性
        $order_info = PayOrder::find()->where([
            'id' => $order_id,
            'status' => -8,
            'member_id' => $this->current_user['id']
        ])->one();
        if(!$order_info){
            return $this->renderJson(-1,'该订单已经失效，请重新下单');
        }
        //调用payapi服务类，获取jsapi参数
        $wx_config = \Yii::$app->params['weixin'];
        $pay_api = new PayApiService($wx_config);
        $notify_url = $wx_config['pay']['notify_url']['m'];
        $openid = $this->getOpenid();
        if(!$openid){
            return $this->renderJson(-1,'请先绑定微信再购买');
        }
        //构建请求所需参数的数组
        $pay_params = [
            'appid' => $wx_config['appid'],
            'mch_id' => $wx_config['pay']['mch_id'],
            'body' => $order_info['note'],
            'out_trade_no' => $order_info['order_sn'],
            'total_fee' => $order_info['pay_price'] * 100,
            'notify_url' => \Yii::$app->params['domain'].UrlService::buildMUrl($notify_url),
            'trade_type' => 'JSAPI',
            'openid' => $openid,

        ];
        $pay_api->setParams($pay_params);
        //获取并设置prepay_id和prepay_info
        $prepay_info = $pay_api->getPrepayInfo();
        if(!$prepay_info){
            return $this->renderJson(-1,'调用微信支付统一下单接口失败，检查参数是否出错');
        }
        //获取jsapi参数
        $js_api = $pay_api->getJsApi();
        return $this->renderJson(200,'获取成功',$js_api);

    }
    //模拟微信支付
    public function actionPrepare2(){
        //支付核心逻辑
        $order_id = intval($this->post('order_id'));
        if(!$order_id){
            return $this->renderJson(-1,'系统繁忙');
        }
        //判断是否在微信端
        if(!UtilService::isWechat()){
            //return $this->renderJson(-1,'请在微信上完成支付');
        }
        //判断订单合法性
        $order_info = PayOrder::find()->where([
            'id' => $order_id,
            'status' => -8,
            'member_id' => $this->current_user['id']
        ])->one();
        if(!$order_info){
            return $this->renderJson(-1,'该订单已经失效，请重新下单');
        }
        $this->actionCallback($order_info['order_sn'],$order_info['pay_price'] * 100);
        return $this->renderJson(200,'支付成功');
    }
    public function actionCallback($order_sn,$total_fee){
        //微信支付后的微信服务器回调的函数
        if(!\Yii::$app->request->isPost){
           //return $this->sendRes('不是post请求',false);
        }
        $xml = file_get_contents('php://input');
        //$this->recordCallback($xml);
        $xml = '<xml>
                  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
                  <attach><![CDATA[支付测试]]></attach>
                  <bank_type><![CDATA[CFT]]></bank_type>
                  <fee_type><![CDATA[CNY]]></fee_type>
                  <is_subscribe><![CDATA[Y]]></is_subscribe>
                  <mch_id><![CDATA[10000100]]></mch_id>
                  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
                  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
                  <out_trade_no><![CDATA['.$order_sn.']]></out_trade_no>
                  <result_code><![CDATA[SUCCESS]]></result_code>
                  <return_code><![CDATA[SUCCESS]]></return_code>
                  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
                  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
                  <time_end><![CDATA[20140903131540]]></time_end>
                  <total_fee>'.$total_fee.'</total_fee>
                  <trade_type><![CDATA[JSAPI]]></trade_type>
                  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
                </xml>';

        $wx_config = \Yii::$app->params['weixin'];
        $pay_api = new PayApiService($wx_config);
        $wx_res = $this->xmlToArray($xml);
        if(!$wx_res){
            return $this->sendRes('没有收到数据',false);
        }
        //检验支付结果是否成功收到
        if($wx_res['return_code'] == 'FAIL' || $wx_res['result_code'] == 'FAIL'){
            return $this->sendRes('业务失败',false);
        }
        //验证签名是否一致
        $check_array = $wx_res;
        unset($check_array['sign']);
        $pay_api->setParams($check_array);
        if(!$pay_api->checkSign($wx_res['sign'])){
            //return $this->sendRes('签名不一致',false);
        }
        //校验返回的订单金额是否与商户侧的订单金额一致
        $order_sn = $wx_res['out_trade_no'];
        $order_info = PayOrder::find()->where(['order_sn' => $order_sn])->asArray()->one();
        if(!$order_info){
            return $this->sendRes('该订单不存在',false);
        }
        if($order_info['pay_price'] * 100 != $wx_res['total_fee']){
            return $this->sendRes('订单金额不一致',false);
        }
        //如果已经处理过该订单，无须再继续发送请求
        if($order_info['status'] == 1){
            return $this->sendRes();
        }
        //处理商户端的数据库
        $params = [
            'pay_sn' => $wx_res['transaction_id'],
        ];
        $res = PayOrderService::orderSuccess($order_info['id'],$params);
        if(!$res){
            return $this->sendRes(PayOrderService::getErrMsg(),false);
        }
        //记录微信发来的回调信息
        $res2 = PayOrderService::setCallbackData($order_info['id'],'pay',$xml);
        if(!$res2){
            return $this->sendRes(PayOrderService::getErrMsg(),false);
        }
        return $this->sendRes();

    }
    //检查微信支付
    //发送给微信支付结果接口的数据
    private function sendRes($reson = 'error',$flag = true,$client_type = 'wechat'){
        $return_code = $flag ? 'SUCCESS' : 'FAIL';
        $return_msg = $flag ? 'OK' : $reson;
        $xml = '<xml>
                  <return_code><![CDATA['.$return_code.']]></return_code>
                  <return_msg><![CDATA['.$return_msg.']]></return_msg>
                </xml>';
        return $xml;
    }
    //获取当前用户的openid
    private function getOpenid(){
        $openid = $this->getCookie($this->auth_cookie_current_openid);
        if(!$openid){
            $openid_info = OauthMemberBind::findOne(['member_id' => $this->current_user['id']]);
            if(!$openid_info || !$openid_info['openid']){
                return false;
            }
            $openid = $openid_info['openid'];
        }
        return $openid;
    }
    //记录错误日志，方便调试
    public function recordCallback($msg){
        $log = new FileTarget();
        $log->logFile = \Yii::$app->getRuntimePath() . "/logs/weixin_callback_".date("Ymd").".log";
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        $log->messages[] = [
            "[url:{$request_uri}][post:".http_build_query($_POST)."] [msg:{$msg}]",
            1,
            'application',
            microtime(true)
        ];
        $log->export();
    }
    
}
