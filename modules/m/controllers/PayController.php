<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;


class PayController extends BaseController{

    public function actionBuy()
    {
    	//购买支付页面
        return $this->render('buy');
    }
    
}
