<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;

use app\models\brand\BrandSetting;

use app\models\WxShareHistory;

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
    //记录微信分享的信息
    public function actionShare(){
        $share_url = trim($this->post('share_url'));
        $member_id = $this->current_user['id']?$this->current_user['id']:'';
        $wxsh = new WxShareHistory();
        $wxsh->member_id = $member_id;
        $wxsh->share_url = $share_url;
        $wxsh->created_time = date('Y-m-d H:i:s');
        $wxsh->save(0);
        return $this->renderJson(200,'记录成功');
    }
}