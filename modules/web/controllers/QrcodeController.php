<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;
use app\common\components\HttpClient;
use app\common\services\weixin\RequestService;
use app\models\market\MarketQrcode;

class QrcodeController extends BaseController{
    
    //渠道二维码列表
    public function actionIndex(){
    	$mix_kw = trim( $this->get("mix_kw","" ) );
        $p = intval( $this->get("p",1) );
        $p = ( $p > 0 )?$p:1;

        $query = MarketQrcode::find();
        if( $mix_kw ){
            $where_name = [ 'LIKE','name','%'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query->andWhere( $where_name );
        }


        //分页功能,需要两个参数，1：符合条件的总记录数量  2：每页展示的数量
        //60,60 ~ 11,10 - 1
        $page_size = 10;
        $page_count = $query->count();
        $page_total = ceil( $page_count / $page_size );


        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset(  ( $p - 1 ) * $page_size  )
            ->limit($page_size)
            ->all( );

        return $this->render("index",[
            'list' => $list,
            'search_conditions' => [
                'status' => '',
                'mix_kw' => $mix_kw,
            ],
            'pages' => [
                'page_count' => $page_count,
                'page_size' => $page_size,
                'page_total' => $page_total,
                'p' => $p
            ]
        ]);
        return $this->render('index');
    }
    //渠道二维码的编辑或添加
    public function actionSet(){
    	
        if(\Yii::$app->request->isPost){
            $id = intval($this->post('id',0));
            $name = trim($this->post('name'));
            $date_now = date('Y-m-d H:i:s');

            if( mb_strlen($name,'utf-8') < 1 ){
                return $this->renderJson(-1,'请输入符合规范的名称');
            }

            $info = MarketQrcode::findOne(['id' => $id]);
          
            if(!$info){
                $model_qrcode = new MarketQrcode();
                $model_qrcode->created_time = $date_now;
            }else{
                $model_qrcode = $info;
            }

            $model_qrcode->name = $name;
            $model_qrcode->updated_time = $date_now;
            if( $model_qrcode->save(0) ){
                //调用微信接口生成二维码
                if(!$model_qrcode->qrcode){
                    $res = $this->getTmpQrcode($model_qrcode->id);
                    if(!$res){
                        return $this->renderJson(-1,'调用微信接口失败');
                    }
                    //插入数据库
                    $model_qrcode->extra = json_encode($res);
                    $model_qrcode->qrcode =  isset($res['url']) ? $res['url'] : '';
                    $model_qrcode->expired_time =  isset($res['expire_seconds']) ? date('Y-m-d H:i:s',time() + $res['expire_seconds']) : '';
                    $model_qrcode->update(0);
                   // $qrcode_image = HttpClient::get( 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($res['ticket']) );

                }
            }

            return $this->renderJson(200,'操作成功');
        }
        //编辑或添加页面展示
        $id = intval($this->get('id',0));
        $info = [];
        if($id){
            $info = MarketQrcode::findOne(['id' => $id]);
        }
        return $this->render('set',['info' => $info]);
    }
    //删除过期二维码
    public function actionDel(){
        $id = intval($this->post('uid',0));
        if(!$id){
            return $this->renderJson(-1,'id不存在');
        }
        $model_qrcode = MarketQrcode::findOne(['id' => $id]);
        $model_qrcode->delete();
        return $this->renderJson(200,'删除成功');
    }
    //从微信获取二维码
    private function getTmpQrcode($id){
        $config = \Yii::$app->params['weixin'];
        RequestService::setConfig( $config['appid'],$config['token'],$config['sk'] );
        $token = RequestService::getAccessToken();
        $array_data = [
            'expire_seconds' => 2591000,
            'action_name' => 'QR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_id' => $id,
                ],
            ],
        ];
        $json_data = json_encode($array_data,JSON_UNESCAPED_UNICODE);
        $res = RequestService::send('qrcode/create?access_token='.$token,$json_data,'POST');
        return $res;
    }
}
