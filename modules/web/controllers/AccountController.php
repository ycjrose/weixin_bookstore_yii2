<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class AccountController extends Controller{
 
    public function actionIndex(){
    	//账户列表
        $this->layout = false;
        return $this->render('index');
    }
    public function actionSet(){
    	//用户编辑或添加
        $this->layout = false;
        return $this->render('set');
    }
    public function actionInfo(){
    	//账户详情
        $this->layout = false;
        return $this->render('info');
    }
}
