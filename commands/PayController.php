<?php 
namespace app\commands;
use app\commands\BaseController;
use app\models\pay\PayOrder;
use app\common\services\PayOrderService;

/**
* 库存处理，释放30分钟往前的订单
*/

class PayController extends BaseController{
	
	public function actionReset_stock(){
		date_default_timezone_set('PRC');
		$before_date = date('Y-m-d H:i:s',time() - 30 * 60);
		$before_orders = PayOrder::find()->where(['status' => -8])->andWhere(['<=','created_time',$before_date])->asArray()->all();
		if(!$before_orders){
			return $this->echoLog('no before data ');
		}
		foreach ($before_orders as $_item) {
			switch ($_item['target_type']) {
				case 1:
					$res = PayOrderService::closeOrder($_item['id']);
					if(!$res){
						return $this->echoLog('do fail:'.$_item['id']);
					}
					break;
			}
		}
		return $this->echoLog('do success!');
	}
}