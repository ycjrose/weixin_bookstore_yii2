<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;

use app\common\services\UrlService; 

use app\models\brand\BrandSetting;

use app\models\brand\BrandImages;

class BrandController extends BaseController{

    public function actionInfo(){
    	//品牌详情
        $brand_info = BrandSetting::find()->asArray()->one();
        return $this->render('info',[
            'brand_info' => $brand_info,
            
        ]);
    }
    public function actionSet(){
    	//品牌编辑
        if(\Yii::$app->request->isPost){
            $all_post = [
                'name' => $this->post('name'),
                'description' => $this->post('description'),
                'address' => $this->post('address'),
                'mobile' => $this->post('mobile'),
                'logo' => $this->post('logo'),
                'updated_time' => date('Y-m-d H:i:s'),
            ];
            //验证
            if(!$all_post['name']){
                return $this->renderJson(-1,'品牌名称不能为空');
            }
            if(!$all_post['logo']){
                return $this->renderJson(-1,'品牌logo没有上传');
            }
            if(!$all_post['description']){
                return $this->renderJson(-1,'品牌描述不能为空');
            }
            if(!$all_post['address']){
                return $this->renderJson(-1,'品牌地址不能为空');
            }
            if(!$all_post['mobile']){
                return $this->renderJson(-1,'品牌电话不能为空');
            }
            //通过
            $brand_info = BrandSetting::find()->one();
            //数据库里有记录就是更新，无记录就是增加（品牌只能有一条记录）
            if($brand_info){
                $brand_info->setAttributes($all_post);
                $brand_info->update(0);
                return $this->renderJson(200,'更新成功');
            }
            $brand = new BrandSetting();
            $all_post['created_time'] = date('Y-m-d H:i:s');
            $brand->setAttributes($all_post);
            $brand->save(0);
            return $this->renderJson(200,'设置成功');

        }
        //页面展示
        $brand_info = BrandSetting::find()->asArray()->one();
        return $this->render('set',[
            'brand_info' => $brand_info,
            
        ]);
    }
    public function actionImages(){
    	//品牌相册
        if(\Yii::$app->request->isPost){
            //上传图片

            $image_key = $this->post('image_key');
            if(!$image_key){
                return $this->renderJson(-1,'上传图片后才能保存');
            }
            //如果图片数量大于5个就不能再上传
            if(BrandImages::find()->asArray()->count() >= 5){
                return $this->renderJson(-1,'图片超过5个，请删除其他图片再重试');
            }
            $images = new BrandImages();
            $images->image_key = $image_key;
            $images->created_time = date('Y-m-d H:i:s');
            $images->save(0);
            return $this->renderJson(200,'保存成功');

        }
        $images_info = BrandImages::find()->asArray()->all();
        return $this->render('images',['images_info' => $images_info]);
    }
    public function actionImages_del(){
        if(\Yii::$app->request->isPost){
            //删除操作
            $action = $this->post('action');
            $id = $this->post('uid');
            if($action != 'remove'){
                return $this->renderJson(-1,'操作失败');
            }
            if(!$id){
                return $this->renderJson(-1,'请指定图片');
            }
            $images_info = BrandImages::find()->where(['id' => $id])->one();
            if(!$images_info){
                return $this->renderJson(-1,'指定的图片不存在');
            }
            $images_info->delete();
            return $this->renderJson(200,'删除图片成功！');

        }
    }
}
