<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;

class StatController extends BaseController{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionIndex(){
    	//财务统计
        return $this->render('index');
    }
    public function actionProduct(){
    	//商品售卖统计
        return $this->render('product');
    }
    public function actionMember(){
    	//会员消费统计
        return $this->render('member');
    }
    public function actionShare(){
        //分享统计
        return $this->render('share');
    }
}
