<?php

namespace app\modules\m\controllers;
use app\modules\m\common\BaseController;
use app\models\member\Member;
use app\models\member\OauthMemberBind;
use app\models\sms\SmsCaptcha;
use app\models\book\Book;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\common\services\UtilService;
use app\common\services\UrlService;
use app\common\services\ContactService;

class UserController extends BaseController{

    public function actionIndex(){
        //用户个人主页面
        return $this->render('index',[
                'current_user' => $this->current_user,
            ]);
    }
    public function actionBind(){
    	//用户登录绑定页面
        if(\Yii::$app->request->isPost){
            $all_post = [
                'mobile' => trim($this->post('mobile')),
                'img_captcha' => trim($this->post('img_captcha')),
                'captcha_code' => trim($this->post('captcha_code')),
            ];

            $openid = $this->getCookie( $this->auth_cookie_current_openid );
            //$openid = 20;
            //验证图形验证码是否正确
            $img_captcha = $this->getCookie('ValidateCode');
            if(strtolower($all_post['img_captcha']) != $img_captcha){
                return $this->renderJson(-1,'图形验证码不正确,请刷新验证码重试');
            }
            //手机验证码是否正确
            $model_sms = new SmsCaptcha();
            if(!$model_sms->checkSmsCaptcha($all_post['mobile'],$all_post['captcha_code'])){
                return $this->renderJson(-1,'手机验证码错误');
            }
            //检查是否是已经绑定的会员
            $member_info = Member::find()->where([ 'mobile' => $all_post['mobile'],'status' => 1 ])->one();

            if( !$member_info ){
                //如果被关闭了
                if( Member::findOne([ 'mobile' => $all_post['mobile']]) ){
                    return $this->renderJSON(-1,'该账号已被禁止，请联系管理员');
                }
                //新增的绑定手机会员
                $model_member = new Member();
                $model_member->nickname = $all_post['mobile'];
                $model_member->mobile = $all_post['mobile'];
                $model_member->setSalt();
                $model_member->reg_ip = sprintf("%u",ip2long( UtilService::getIP() ) );
                $model_member->status = 1;
                $model_member->created_time = $model_member->updated_time = date("Y-m-d H:i:s");
                $model_member->save( 0 );
                $member_info = $model_member;
            }
            //已经有openid就绑定
            if( $openid ){
                //判断是否绑定过
                $bind_info = OauthMemberBind::find()->where([ 'member_id' => $member_info['id'],'openid' => $openid,'type' => 1  ])->one();

                if( !$bind_info ){
                    $model_bind = new OauthMemberBind();
                    $model_bind->member_id = $member_info['id'];
                    $model_bind->type = 1;
                    $model_bind->client_type = "weixin";
                    $model_bind->openid = $openid;
                    $model_bind->unionid = '';
                    $model_bind->extra = '';
                    $model_bind->updated_time = date("Y-m-d H:i:s");
                    $model_bind->created_time = date("Y-m-d H:i:s");
                    $model_bind->save( 0 );
                    //绑定之后要做的事情
                
                }
            }


            $this->removeCookie('ValidateCode');
            //若没有获取授权信息，跳转到授权页面
            if( UtilService::isWechat() && $member_info['nickname']  == $member_info['mobile'] ){
                return $this->renderJSON(200,'绑定成功~~',[ 
                    'url' => UrlService::buildMUrl( "/oauth/login",[ 'scope' => 'snsapi_userinfo' ] )  
                    ]);
            }

            
            //设置登录态
            $this->setLoginStatus( $member_info );
            return $this->renderJson(200,'登陆成功');
        }
        return $this->render('bind');
    }
    public function actionCart(){
    	//我的购物车页面
    	return $this->render('cart');
    }
    public function actionOrder(){
    	//我的订单页面
        //所有的订单项（一个订单可以有多个商品）
        $orders = PayOrder::find()->where(['member_id' => $this->current_user['id']])->orderBy(['id' => SORT_DESC])->asArray()->all();
        $list = [];
        if($orders){
            //所有的订单的商品项
            $pay_items = PayOrderItem::find()->where(['member_id' => $this->current_user['id'],'pay_order_id' => array_column($orders, 'id')])->orderBy(['id' => SORT_DESC])->asArray()->all();
            //所有的订单书籍的信息
            $books = Book::find()->where(['id' => array_column($pay_items, 'target_id')])->indexBy('id')->asArray()->all();
            //对订单商品表按订单号进行分类存在数组里并构建前台需要的数组
            $pay_items_table = [];
            foreach ($pay_items as $_item) {
                $book_info = $books[$_item['target_id']];
                //如果还没有声明，则必须声明
                if(!isset($pay_items_table[$_item['pay_order_id']])){
                    $pay_items_table[$_item['pay_order_id']] = [];
                }
                $pay_items_table[$_item['pay_order_id']][] = [
                    'price' => $_item['price'],
                    'book_name' => $book_info['name'],
                    'book_main_image' => $book_info['main_image'],
                    'book_id' => $book_info['id'],
                    'comment_status' => $_item['comment_status'],
                ];
            }
            //按订单分类构建前台所需数组
            foreach ($orders as $_order) {
                $list[] = [
                    'id' => $_order['id'],
                    'sn' => $_order['order_sn'],
                    'created_time' => $_order['created_time'],
                    'pay_price' => $_order['pay_price'],
                    'items' => $pay_items_table[$_order['id']],
                    'status' => $_order['status'],
                    'comment_status' => $_order['comment_status'],
                    'express_status' => $_order['express_status'],
                    'express_info' => $_order['express_info'],
                    'pay_url' => UrlService::buildMUrl('/pay/buy/?pay_order_id='.$_order['id']),
                ];
            }
        }

    	return $this->render('order',['list' => $list]);
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
