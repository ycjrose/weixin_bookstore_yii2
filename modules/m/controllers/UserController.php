<?php

namespace app\modules\m\controllers;

use yii\web\Controller;


class UserController extends Controller{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
    public function actionBind()
    {
    	//用户登录绑定页面
        return $this->render('bind');
    }
    public function actionCart(){
    	//我的购物车页面
    	return $this->render('cart');
    }
    public function actionOrder(){
    	//我的订单页面
    	return $this->render('order');
    }
    public function actionIndex(){
    	//用户个人主页面
    	return $this->render('index');
    }
    public function actionAddress(){
    	//我的收货地址
    	return $this->render('address');
    }
    public function actionAddress_set(){
    	//我的地址添加与编辑页面
    	return $this->render('address_set');
    }
    public function actionFav(){
    	//我的收藏页面
    	return $this->render('fav');
    }
    public function actionComment(){
    	//我的评论页面
    	return $this->render('comment');
    }
    public function actionComment_set(){
    	//我的评论编辑
    	return $this->render('comment_set');
    }

}