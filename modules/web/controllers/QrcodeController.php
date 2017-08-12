<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;

class QrcodeController extends BaseController{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionIndex(){
    	//渠道二维码列表
        return $this->render('index');
    }
    public function actionSet(){
    	//渠道二维码的编辑或添加
        return $this->render('set');
    }
    
}
