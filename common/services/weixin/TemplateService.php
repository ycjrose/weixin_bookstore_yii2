<?php
namespace app\common\services\weixin;

use app\common\services\BaseService;
use app\common\components\HttpClient; 
use app\common\services\weixin\RequestService;
use app\common\services\UrlService;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\models\book\Book;
use app\models\member\OauthMemberBind;
/**
* 处理微信模板消息的类
*/
class TemplateService extends BaseService{

	//支付成功后微信的模板消息提醒
	public static function payNotice($order_id){
		//查询订单信息
		$order_info = PayOrder::findOne(['id' => $order_id,'status' => 1]);
		if(!$order_info){
			return self::_err(-1,'没有找到该订单');
		}
		//查询购买的书籍名称与数量的数组
		$pay_items = PayOrderItem::find()->where(['pay_order_id' => $order_id])->asArray()->all();
		$books = Book::find()->where(['id' => array_column($pay_items, 'target_id')])->indexBy('id')->asArray()->all();
		$productName_array = [];
		foreach($pay_items as $_item){

			$productName_array[] = $books[$_item['target_id']]['name'] .'*'.$_item['quantity'];
		}
		$productName = implode(',', $productName_array);
		//获取openid
		$config = \Yii::$app->params['weixin'];
		RequestService::setConfig( $config['appid'],$config['token'],$config['sk'] );
		$openid = self::getOpenId($order_info['member_id']);
		if(!$openid){
			return self::_err(-1,self::getErrMsg());
		}
		//构建所发送的数组
		$array_data = [
			'touser' => $openid,
			'template_id' => '8JhBgybnlkUvEXTqp4aBgdiSTcKNN7nk0S6TshbI4Wc',
			'url' => \Yii::$app->params['domain'].UrlService::buildMUrl('/user/order'),
			'data' => [
				'first' => [
					'value' => '我们已收到您的货款，开始为您打包商品，请耐心等待: )',
					'color' => '#173177'
				],
				'orderMoneySum' => [
					'value' => $order_info['pay_price'],
					'color' => '#fb0909'
				],
				'orderProductName' => [
					'value' => $productName,
					'color' => '#173177'
				],
				'remark' => [
					'value' => '点击查看详情',
					'color' => '#173177'
				],

			],
		];
		$json_data = json_encode($array_data,JSON_UNESCAPED_UNICODE);
		$access_token = RequestService::getAccessToken();
		$url = 'message/template/send?access_token='.$access_token;
		//发起接口请求
		$res = RequestService::send($url,$json_data,'POST');

	}
	/**
     * 获取微信公众平台的微信公众号id
     */
    protected static function getOpenId( $member_id ){
        $open_infos = OauthMemberBind::findAll([ 'member_id' => $member_id,'type' => 1 ]);

        if( !$open_infos ){
            return self::_err(-1,'该会员没有绑定第三方账号');
        }

        foreach($open_infos as $open_info){
            if( self::getPublicByOpenId($open_info['openid']) ) {
                return $open_info['openid'];
            }
        }
        return self::_err(-1,'该会员没有关注该公众号');
    }
    //判断用户是否关注了公众号
	protected static function getPublicByOpenId($openid){
        $token = RequestService::getAccessToken();
		$info = RequestService::send("user/info?access_token={$token}&openid={$openid}&lang=zh_CN");
        if(!$info || isset($info['errcode']) ){
            return false;
        }

        if($info['subscribe']){
            return true;
        }
        return false;
    }
}