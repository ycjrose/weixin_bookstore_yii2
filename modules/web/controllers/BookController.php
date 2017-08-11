<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class BookController extends Controller{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }

    public function actionIndex(){
    	//图书列表
        return $this->render('index');
    }
    public function actionSet(){
    	//图书编辑或添加
        return $this->render('set');
    }
    public function actionInfo(){
    	//图书详情
        return $this->render('info');
    }
    public function actionImages(){
        //图书图片资源
        return $this->render('images');
    }
    public function actionCat(){
        //分类列表
        return $this->render('cat');
    }
    public function actionCat_set(){
        //图书分类的编辑和添加
        return $this->render('cat_set');
    }
}
