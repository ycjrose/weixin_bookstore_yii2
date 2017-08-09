<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class BrandController extends Controller{
 
    public function actionInfo(){
    	//品牌详情
        $this->layout = false;
        return $this->render('info');
    }
    public function actionSet(){
    	//品牌编辑
        $this->layout = false;
        return $this->render('set');
    }
    public function actionImages(){
    	//品牌相册
        $this->layout = false;
        return $this->render('images');
    }
}
