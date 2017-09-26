<?php

namespace app\common\services;

use app\common\services\BaseService;
use app\common\services\book\BookService;
use app\common\services\weixin\TemplateService;
use app\models\book\Book;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderCallbackData;
use app\models\pay\PayOrderItem;
use \Exception;

class PayOrderService extends BaseService{
	//创建订单服务
	public static function createPayOrder($member_id ,$items = [], $params = []){
		date_default_timezone_set('PRC');
		//计算商品所需要付的总价
		$total_price = 0;
		$continue_cnt = 0;
		foreach( $items as $_item ){
			if( $_item['price'] < 0 ){
				$continue_cnt += 1;
				continue;
			}
			$total_price += $_item['price'];
		}

		if( $continue_cnt >= count($items) ){
			return self::_err( '商品items为空~~');
		}
		//支付 = 总价 - 折扣价
		$discount = isset( $params['discount'] )?$params['discount']:0;
		$total_price = sprintf("%.2f",$total_price);
		$discount = sprintf("%.2f",$discount);
		$pay_price = $total_price - $discount;
		$pay_price = sprintf("%.2f",$pay_price);
		$date_now = date("Y-m-d H:i:s");

		//开启数据库事务
		$connection =  PayOrder::getDb();
		$transaction = $connection->beginTransaction();
		try{
			//为了防止并发 库存出问题了，select for update
			$tmp_book_table_name = Book::tableName();
			$tmp_book_ids = array_column( $items,'target_id' );
			$tmp_sql = "SELECT id,stock FROM {$tmp_book_table_name} WHERE id in (".implode(",",$tmp_book_ids).") FOR UPDATE";
			$tmp_book_list = $connection->createCommand($tmp_sql)->queryAll();
			$tmp_book_unit_mapping = [];
			//把取出来的id与库存的内容变成键和值的对应关系
			foreach( $tmp_book_list as $_book_info ){
				$tmp_book_unit_mapping[ $_book_info['id'] ] = $_book_info['stock'];
			}

			//插入订单表
			$model_pay_order = new PayOrder();
			$model_pay_order->order_sn = self::generate_order_sn();
			$model_pay_order->member_id = $member_id;
			$model_pay_order->pay_type = isset($params['pay_type'])?$params['pay_type']:0;
			$model_pay_order->pay_source = isset($params['pay_source'])?$params['pay_source']:0;
			$model_pay_order->target_type = isset($params['target_type'])?$params['target_type']:0;
			$model_pay_order->total_price = $total_price;
			$model_pay_order->discount = $discount;
			$model_pay_order->pay_price = $pay_price;
			$model_pay_order->note = isset($params['note'])?$params['note']:'';
			$model_pay_order->status = isset($params['status'])?$params['status']:-8;
			$model_pay_order->express_status = isset($params['express_status'])?$params['express_status']:-8;
			$model_pay_order->express_address_id = isset($params['express_address_id'])?$params['express_address_id']:0;
			$model_pay_order->pay_time ='';
			$model_pay_order->updated_time = $date_now;
			$model_pay_order->created_time = $date_now;
			if( !$model_pay_order->save(0) ){
				throw new Exception("创建订单失败~~");
			}

			//插入具体订单表，下单成功后生成的表（每条记录对应一种书的记录）
			foreach($items as $_item){

				$tmp_left_stock = $tmp_book_unit_mapping[ $_item['target_id'] ];
				if( $tmp_left_stock < $_item['quantity'] ){
					throw new Exception("购买书籍库存不够,目前剩余库存：{$tmp_left_stock},你购买:{$_item['quantity']}");
				}

				if( !Book::updateAll( [ 'stock' => $tmp_left_stock - $_item['quantity'] ],[ 'id' => $_item['target_id'] ] ) ){
					throw new Exception("下单失败请重新下单");
				}

				$new_item = new PayOrderItem();
				$new_item->pay_order_id = $model_pay_order->id;
				$new_item->member_id = $member_id;
				$new_item->quantity  = $_item['quantity'];
				$new_item->price  = $_item['price'];
				$new_item->target_type  = $_item['target_type'];
				$new_item->target_id  = $_item['target_id'];
				$new_item->status = isset($_item['status'])?$_item['status']:1;

				if( isset( $_item['extra_data'] ) ){
					$new_item->extra_data = json_encode( $_item['extra_data'] );
				}

				$new_item->note = isset( $_item['note'] )?$_item['note']:"";
				$new_item->updated_time = $date_now;
				$new_item->created_time  = $date_now;
				if( !$new_item->save(0) ){
					throw new Exception("创建订单失败");
				}

				BookService::setStockChange( $_item['target_id'],-$_item['quantity'],"在线购买" );

			}

			$transaction->commit();

			return [
				'id' => $model_pay_order->id,
				'order_sn' => $model_pay_order->order_sn,
				'pay_money' => $model_pay_order->pay_price,
			];

		}catch(Exception $e){
			$transaction->rollBack();
			return self::_err(-1,$e->getMessage());
		}
	}
	//支付成功后的订单处理
	public static function orderSuccess($order_id,$params = []){
		date_default_timezone_set('PRC');
		$date_now = date('Y-m-d H:i:s');
		//开启事务
		$connection = PayOrder::getDb();
		$transaction = $connection->beginTransaction();
		try{
			$order_info = PayOrder::find()->where(['id' => $order_id])->one();
			if(!$order_info || !in_array($order_info['status'],[-7,-8] )){
				throw new Exception('订单不存在或状态不正常');
			}
			//更新订单表
			$order_info->status = 1;
			$order_info->express_status = -7;
			$order_info->pay_time = $date_now;
			$order_info->updated_time = $date_now;
			$order_info->pay_sn = isset($params['pay_sn']) ? $params['pay_sn'] : '';
			$order_info->update(0);
			//记录订单每个商品的售卖情况
			$pay_items = PayOrderItem::find()->where(['pay_order_id' => $order_id])->asArray()->all();
			foreach ($pay_items as $_item) {
				switch ($_item['target_type']) {
					case 1:
						//书籍类相关操作
						$res = BookService::setSaleChange($_item['id']);
						if(!$res){
							throw new Exception('记录售卖情况失败');
						}
						break;
				}
			}
			$transaction->commit();
		}catch(Exception $e){
			$transaction->rollBack();
			return self::_err(-1,$e->getMessage());
		}
		$res = TemplateService::payNotice($order_info['id']);
		return true;
	}
	//记录微信发来的异步回调信息
	public static function setCallbackData($order_id,$type,$xml){
		if(!$order_id){
			return self::_err(-1,'订单号不能为空');
		}
		if(!in_array($type, ['pay','refund'])){
			return self::_err(-1,'类型参数错误');
		}
		$order_info = PayOrder::findOne(['id' => $order_id]);
		if(!$order_info){
			return self::_err(-1,'找不到订单');
		}

		//如果已经存在该订单的信息，那就更新
		date_default_timezone_set('PRC');
		$date_now = date('Y-m-d H:i:s');
		$callbackData = PayOrderCallbackData::find()->where(['pay_order_id' => $order_id])->one();
		if(!$callbackData){
			$callbackData = new PayOrderCallbackData();
			$callbackData->pay_order_id = $order_id;
			$callbackData->created_time = $date_now;
		}
		if($type == 'refund'){
			$callbackData->pay_data = '';
			$callbackData->refund_data = $xml;
		}else{
			$callbackData->pay_data = $xml;
			$callbackData->refund_data = '';
		}
		$callbackData->updated_time = $date_now;
		$callbackData->save(0);
		return true;
	}
	//取消订单处理
	public static function closeOrder($order_id){
		date_default_timezone_set('PRC');
		$date_now = date('Y-m-d H:i:s');
		$order_info = PayOrder::find()->where(['id' => $order_id,'status' => -8])->one();
		if(!$order_info){
			return self::_err(-1,'指定订单不存在');
		}
		$pay_items = PayOrderItem::findAll(['pay_order_id' => $order_id]);
		if(!$pay_items){
			return self::_err(-1,'商品不存在');
		}
		foreach ($pay_items as $_item) {
			$book_info = Book::find()->where(['id' => $_item['target_id']])->one();
			switch ($_item['target_type']) {
				case 1:
					$book_info->stock += $_item['quantity'];
					$book_info->updated_time = $date_now;
					$book_info->update(0);
					BookService::setStockChange($_item['target_id'],$_item['quantity'],'取消订单'); 
					break;
				
			}
		}
		$order_info->status = 0;
		$order_info->updated_time = $date_now;
		return $order_info->update(0);

	}
	//生成随机订单号
	public static function generate_order_sn(){
		do{
			$sn = md5(microtime(1).rand(0,9999999).'-|eeg*@');

		}while( PayOrder::findOne( [ 'order_sn' => $sn ] ) );

		return $sn;
	}
}