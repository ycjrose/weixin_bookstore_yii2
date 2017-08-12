<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;

class MemberController extends BaseController{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionIndex(){
    	//会员列表页
        return $this->render('index');
    }
    public function actionInfo(){
    	//会员详情页
        return $this->render('info');
    }
    public function actionSet(){
    	//会员信息的编辑或添加
        return $this->render('set');
    }
    public function actionComment(){
        //会员的评论列表
        return $this->render('comment');
    }
}
