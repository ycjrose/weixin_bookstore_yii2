<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class UserController extends Controller{
 
    public function actionLogin(){
    	//登陆页面
        $this->layout = false;
        return $this->render('login');
    }
    public function actionEdit(){
    	//编辑当前登陆人信息的页面
        $this->layout = false;
        return $this->render('edit');
    }
    public function actionResetPwd(){
    	//重置当前登陆人密码的页面
        $this->layout = false;
        return $this->render('reset_pwd');
    }
}
