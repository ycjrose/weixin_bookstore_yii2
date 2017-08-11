<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class FinanceController extends Controller{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionIndex(){
    	//订单列表
        return $this->render('index');
    }
    public function actionAccount(){
    	//财务流水
        return $this->render('account');
    }
    public function actionPay_info(){
    	//订单详情
        return $this->render('pay_info');
    }
}
