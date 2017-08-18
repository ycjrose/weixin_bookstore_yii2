<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;

use app\models\brand\BrandSetting;
use app\models\brand\BrandImages;


class DefaultController extends BaseController{

    public function actionIndex()
    {
    	//品牌首页
    	$brand_info = BrandSetting::find()->asArray()->one();
    	$images = BrandImages::find()->orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->render('index',[
        	'brand_info' => $brand_info,
        	'images' => $images,
        ]);
    }
}