<?php 
namespace app\commands;
use app\commands\BaseController;
use app\common\services\weixin\TemplateService;
use app\models\QueueList;
use app\models\market\MarketQrcode;
use app\models\market\QrcodeScanHistory;

//队列消费者

class QueueListController extends BaseController{
	//绑定手机推送消息处理队列
	public function actionBind_list(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1',7200);
		$val = $redis->rPop('bind');//member_id,openid,microtime
		
		$this->popQueue($val,'bind');

		
	}
	//订单成功处理队列
	public function actionPay_list(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1',7200);
		$val = $redis->rPop('pay');//member_id,order_id,microtime
		
		$this->popQueue($val,'pay');
		
	}
	//发货消息处理队列
	public function actionExpress_list(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1',7200);
		$val = $redis->rPop('express');//member_id,order_id,microtime
		
		$this->popQueue($val,'express');
	}
	//处理队列函数
	private function popQueue($val,$act){
		if(!$val){
			echo '没有队列了';
			exit;
		}
		$val_array = explode(',',$val);
		
		//发送模板
		switch ($act) {
			case 'bind':
				//记录通过扫码关注的用户的数量
				$scan_info = QrcodeScanHistory::find()->where([ 'openid' => $val_array[1] ])->one();
				if( $scan_info ){
					$qrcode_info = MarketQrcode::find()->where([ 'id' => $scan_info['qrcode_id'] ])->one();
					if( $qrcode_info ){
						$qrcode_info->total_reg_count += 1;
						$qrcode_info->update( 0 );
					}
				}
				
				//发送消息模板
				$res = TemplateService::bindNotice($val_array[0]);
				break;
			
			case 'pay':
				$res = TemplateService::payNotice($val_array[1]);
				break;

			case 'express':
				$res = TemplateService::expressNotice($val_array[1]);
				break;
		}
		
		//记录每次消费结果
		$queue_info = new QueueList();
		$queue_info->queue_name = $act;
		$queue_info->data = $val_array[0].'%'.$val_array[1];
		if($res){
			$queue_info->status = 1;
		}else{
			$queue_info->status = -1;
			//消费失败，把数据压回队列
			$redis->rPush($act,$val);
			
		}
		$queue_info->created_time = $val_array[2];
		$queue_info->save(0);
		if($queue_info->status){
			echo '处理成功';
		}else{
			echo '处理失败,'.TemplateService::getErrMsg();
		}
	}

}