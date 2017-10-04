<?php

namespace app\modules\m\controllers;
use app\modules\m\common\BaseController;
use app\models\member\Member;
use app\models\member\MemberCart;
use app\models\member\MemberAddress;
use app\models\member\MemberFav;
use app\models\member\MemberComments;
use app\models\member\OauthMemberBind;
use app\models\sms\SmsCaptcha;
use app\models\book\Book;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\models\City;
use app\common\services\UtilService;
use app\common\services\UrlService;
use app\common\services\ContactService;
use app\common\services\QueueListService;
use app\common\services\AreaService;

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
                    //给用户发送模板消息的队列
                    $val = [
                        'member_id' => $member_info['id'],
                        'openid' => $model_bind->openid,
                        'created_time' => microtime(),
                    ];

                    QueueListService::addQueue('bind',$val);

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
        $carts = MemberCart::find()->where( ['member_id' => $this->current_user['id']] )->orderBy(['id' => SORT_DESC])->asArray()->all();
        if(!$carts){
            return $this->render('cart' , ['carts' => 0]);
        }
        $books = Book::find()->where( ['id' => array_column($carts, 'book_id')] )->indexBy('id')->asArray()->all();
        foreach($carts as $k => $v){

            $carts[$k]['book_name'] = $books[ $v['book_id'] ]['name'];
            $carts[$k]['main_image'] = $books[ $v['book_id'] ]['main_image'];
            $carts[$k]['book_price'] = $books[ $v['book_id'] ]['price'];
            $carts[$k]['book_stock'] = $books[ $v['book_id'] ]['stock'];
        }
    	
    	return $this->render('cart' , ['carts' => $carts]);
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
        $list = MemberAddress::find()->where([ 'member_id' => $this->current_user['id'],'status' => 1 ])->orderBy([ 'is_default' => SORT_DESC,'id' => SORT_DESC ])->asArray()->all();
        $citys = City::find(['province','city','area'])->where(['id' => array_column($list, 'area_id')])->indexBy('id')->asArray()->all();
        foreach ($list as $k => $v) {
            $city_info = $citys[ $v['area_id'] ];
            $list[$k]['really_address'] = $city_info['province'].$city_info['city'].$city_info['area'].$v['address'];
        }
    	return $this->render('address',['list' => $list]);
    }
    public function actionAddress_set(){
        //提交数据的处理
    	if(\Yii::$app->request->isPost){
            $id = intval( $this->post("id",0) );
            $nickname = trim( $this->post("nickname","") );
            $mobile = trim( $this->post("mobile","") );
            $province_id = intval( $this->post("province_id",0) );
            $city_id = intval( $this->post("city_id",0) );
            $area_id = intval( $this->post("area_id",0) );
            $address = trim( $this->post("address","" ) );
            $date_now = date("Y-m-d H:i:s");

            if( mb_strlen( $nickname,"utf-8" ) < 1 ){
                return $this->renderJSON(-1,'请输入符合规范的收货人姓名~~');
            }

            if( !preg_match("/^[1-9]\d{10}$/",$mobile) ){
                return $this->renderJSON(-1,'请输入符合规范的收货人手机号码~~');
            }

            if( $province_id < 1 ){
                return $this->renderJSON(-1,'请选择省~~');
            }

            if( $city_id < 1 ){
                return $this->renderJSON(-1,'请选择市~~');
            }

            if( $area_id < 1 ){
                return $this->renderJSON(-1,'请选择区~~');
            }

            if( mb_strlen( $address,"utf-8" ) < 3 ){
                return $this->renderJSON(-1,'请输入符合规范的收货人详细地址~~');
            }
            //判断是编辑还是新增
            $info = [];
            if( $id ){
                $info = MemberAddress::find()->where([ 'id' => $id,'member_id' => $this->current_user['id'] ])->one();
            }

            if( $info ){//编辑
                $model_address = $info;
            }else{//新增
                $model_address = new MemberAddress();
                $model_address->member_id = $this->current_user['id'];
                $model_address->status = 1;
                $model_address->created_time = $date_now;
            }

            $model_address->nickname = $nickname;
            $model_address->mobile = $mobile;
            $model_address->province_id = $province_id;
            $model_address->city_id = $city_id;
            $model_address->area_id = $area_id;
            $model_address->address = $address;
            $model_address->updated_time = $date_now;
            $model_address->save( 0 );

            return $this->renderJson(200,'更新地址成功');
        }
        //我的地址添加与编辑页面
        $id = intval($this->get('id',0));
        $info = [];
        if($id){
            $info = MemberAddress::find()->where(['member_id' => $this->current_user['id'],'id' => $id])->one();
        }
    	return $this->render('address_set',[
            'info' => $info,
            'provinces' => AreaService::getProvinces(),
        ]);
    }
    public function actionAddress_ops(){

        $id = intval($this->post('id',0));
       
        $act = trim($this->post('act'));

        if(!$id || !$act){
            return $this->renderJson(-1,'系统错误');
        }
        if( !in_array($act, ['del','set_default']) ){

            return $this->renderJson(-1,'行为错误');
        }

        $info = MemberAddress::find()->where([ 'member_id' => $this->current_user['id'],'id' => $id ])->one();

        switch ( $act ){
            case "del":
                $info->is_default = 0;
                $info->status = 0;
                break;
            case "set_default":
                $info->is_default = 1;
                break;
        }

        $info->updated_time = date("Y-m-d H:i:s");
        $info->update( 0 );

        if( $act == "set_default" ){
            MemberAddress::updateAll(
                [ 'is_default' => 0  ],
                [ 'AND',[ 'member_id' => $this->current_user['id'],'status' => 1 ] ,[ '!=','id',$id ] ]
            );
        }

        return $this->renderJson(200,'操作成功');
    }
    public function actionFav(){
    	//我的收藏页面
        $favs = MemberFav::find()->where( ['member_id' => $this->current_user['id']] )->orderBy(['id' => SORT_DESC])->asArray()->all();
        if(!$favs){
            return $this->render('fav' , ['favs' => 0]);
        }
        $books = Book::find()->where( ['id' => array_column($favs, 'book_id')] )->indexBy('id')->asArray()->all();
        foreach($favs as $k => $v){

            $favs[$k]['book_name'] = $books[ $v['book_id'] ]['name'];
            $favs[$k]['main_image'] = $books[ $v['book_id'] ]['main_image'];
            $favs[$k]['book_price'] = $books[ $v['book_id'] ]['price'];
            $favs[$k]['book_stock'] = $books[ $v['book_id'] ]['stock'];
        }
        
        return $this->render('fav' , ['favs' => $favs]);
    }
    public function actionComment(){
    	//我的评论页面
        $list = MemberComments::find()->where([ 'member_id' => $this->current_user['id'] ])
            ->orderBy([ 'id' => SORT_DESC ])->asArray()->all();
        if(!$list){
            return $this->render('comment',[
                'list' => 0
            ]);
        }
        $orders = PayOrder::find()->where( ['id' => array_column($list, 'pay_order_id')] )->indexBy('id')->asArray()->all(); 
        $books = Book::find()->where( ['id' => array_column($list, 'book_id')] )->indexBy('id')->asArray()->all();

        foreach($list as $k => $v){

            $list[$k]['book_name'] = $books[ $v['book_id'] ]['name'];
            $list[$k]['order_sn'] = $orders[ $v['pay_order_id'] ]['order_sn'];
        }

        return $this->render('comment',[
            'list' => $list
        ]);
    }
    public function actionComment_set(){
    	//我的评论编辑
        if(\Yii::$app->request->isPost){

            $pay_order_id = intval( $this->post("pay_order_id",0) );
            $book_id = intval( $this->post('book_id',0) );
            $score = floatval( $this->post("score",0) );
            $content = trim( $this->post('content') );
            $date_now  = date("Y-m-d H:i:s");

            if(!$score){
                return $this->renderJson(-1,'评分不正确');
            }

            if(!$content){
                $content = ContactService::$default_comment;
            }
            $order_info = PayOrder::find()->where(['id' => $pay_order_id,'status' => 1,'express_status' => 1 ])->one();
            if(!$order_info){
                return $this->renderJson(-1,'订单不存在或还没有签收');
            }
            
            $pay_item = PayOrderItem::find()->where(['pay_order_id' => $pay_order_id,'target_id' => $book_id])->one();
            if(!$pay_item){
                return $this->renderJson(-1,'该商品不存在');
            }
            if($pay_item['comment_status']){
                return $this->renderJson(-1,'已经评论过啦！');
            }
            $book_info = Book::findOne([ 'id' => $book_id ]);
            if( !$book_info ){
                return $this->renderJson(-1,'书本信息不存在');
            }
            //将评论插入数据表
            $model_comment = new MemberComments();
            $model_comment->member_id = $this->current_user['id'];
            $model_comment->book_id = $book_id;
            $model_comment->pay_order_id = $pay_order_id;
            $model_comment->score = $score * 2;
            $model_comment->content = $content;
            $model_comment->created_time = $date_now;
            $model_comment->save( 0 );

            $pay_item->comment_status = 1;
            $pay_item->update( 0 );

            $book_info->comment_count += 1;
            $book_info->update( 0 );
            return $this->renderJson(200,'评论成功~~');

        }
        //页面展示
        $pay_order_id = intval($this->get('pay_order_id'));
        $book_id = intval($this->get('book_id'));
        $order_info = PayOrder::find()->where(['id' => $pay_order_id,'status' => 1,'express_status' => 1 ])->one();
        $reback_url = UrlService::buildMUrl('/user');
        if(!$order_info){
            return $this->redirect($reback_url);
        }
        if($order_info['comment_status']){
            return $this->redirect($reback_url);
        }
        $pay_item = PayOrderItem::find()->where(['pay_order_id' => $pay_order_id,'target_id' => $book_id])->one();
        if(!$pay_item || $pay_item['comment_status']){
            return $this->redirect($reback_url);
        }
    	return $this->render('comment_set',[
            'pay_order_id' => $pay_order_id,
            'book_id' => $book_id,
        ]);
    }

}
