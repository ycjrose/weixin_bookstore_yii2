<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;


class ProductController extends BaseController{

    public function actionIndex()
    {
    	//商品列表页w
        return $this->render('index');
    }
    public function actionInfo(){
    	//商品详情页w
    	return $this->render('info');
    }
    public function actionOrder(){
    	//下订单页面w
    	return $this->render('order');
    }
}
