<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\PayOrderService;
use app\models\pay\PayOrder;


class OrderController extends BaseController{
	//订单操作
	public function actionOps(){
		if(\Yii::$app->request->isPost){
			$action = trim($this->post('action'));
			$id = intval($this->post('id',0));
			if(!in_array($action, ['remove','confirm_express'])){
				return $this->renderJson(-1,'行为出错');
			}
			if(!$id){
				return $this->renderJson(-1,'订单号不存在');
			}
			$order_info = PayOrder::findOne(['id' => $id,'member_id' => $this->current_user['id']]);
			if(!$order_info){
				return $this->renderJson(-1,'订单不存在');
			}
			switch ($action) {
				case 'remove':
					if($order_info['status'] == -8){
						$res = PayOrderService::closeOrder($id);
						if(!$res){
							return $this->renderJson(-1,'取消订单失败');
						}
					}
					
					break;
				case 'confirm_express':
					$order_info->express_status = 1;
					$order_info->updated_time = date('Y-m-d H:i:s');
					$order_info->update(0);
					break;
				
			}
			return $this->renderJson(200,'操作成功');
		}
	}
}