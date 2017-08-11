<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class AccountController extends Controller{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }

    public function actionIndex(){
    	//账户列表
        return $this->render('index');
    }
    public function actionSet(){
    	//用户编辑或添加
        return $this->render('set');
    }
    public function actionInfo(){
    	//账户详情
        return $this->render('info');
    }
}
