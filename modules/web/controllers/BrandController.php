<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class BrandController extends Controller{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionInfo(){
    	//品牌详情
        return $this->render('info');
    }
    public function actionSet(){
    	//品牌编辑
        return $this->render('set');
    }
    public function actionImages(){
    	//品牌相册
        return $this->render('images');
    }
}
