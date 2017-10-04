<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\PayOrderService;
use app\models\book\Book;
use app\models\City;
use app\models\member\MemberCart;
use app\models\member\MemberFav;
use app\models\member\MemberAddress;

class ProductController extends BaseController{

    public function actionIndex(){
    	//商品列表页
        $kw = trim( $this->get("kw") );
        $sort_field = trim( $this->get("sort_field","default") );
        $sort = trim( $this->get("sort") );
        $sort = in_array(  $sort,['asc','desc'] )?$sort:'desc';

        $books = $this->getBooks();

        return $this->render('index',[
            'books' => $books,
            'search_conditions' => [
                'kw' => $kw,
                'sort_field' => $sort_field,
                'sort' => $sort,
            ],
        ]);
    }
    public function actionInfo(){
    	//商品详情页
        $id = intval($this->get('id',0));
        $reback_url = UrlService::buildMUrl('/product');
        if(!$id){    
            return $this->redirect($reback_url);
        }
        $book_info = Book::find()->where(['id' => $id])->asArray()->one();
        if(!$book_info){
            return $this->redirect($reback_url);
        }

        $has_faved = false;
        if(  $this->current_user ){
            $has_faved = MemberFav::find()->where([ 'member_id' => $this->current_user['id'],'book_id' => $id ])->count();
        }

    	return $this->render('info',[
            'book_info' => $book_info,
            'has_faved' => $has_faved,
        ]);
    }
    public function actionOrder(){
        if(\Yii::$app->request->isPost){
            $sc = trim( $this->post("sc","") );
            $product_item = $this->post('product_item',[]);
            $address_id = intval($this->post('address_id'));

            if(!$address_id){
                return $this->renderJson(-1,'地址不存在');
            }
            if(!$product_item){
                return $this->renderJson(-1,'没有选择书籍');
            }
            $book_ids = [];
            foreach ($product_item as $value) {
                $product_info = explode('#', $value);
                $book_ids[] = $product_info[0];
            }
            $books = Book::find()->where(['id' => $book_ids,'status' => 1])->indexBy('id')->asArray()->all();
            if(!$books){
                return $this->renderJson(-1,'没有选择书籍');
            }
            //下订单服务
            $target_type = 1;
            $items = [];
            foreach ($product_item as $value) {
                $product_info = explode('#', $value);
                $book_info = $books[$product_info[0]];
                $items[] = [
                    'target_id' => $product_info[0],
                    'target_type' => $target_type,
                    'price' => $book_info['price'] * $product_info[1],
                    'quantity' => $product_info[1],
                ];  
            }
            $params = [
                'pay_type' => 1,
                'pay_source' => 2,
                'target_type' => $target_type,
                'note' => '购买书籍',
                'status' => -8,
                'express_address_id' => $address_id,
            ];
            //调用创建订单服务
            $res = PayOrderService::createPayOrder($this->current_user['id'],$items,$params);
            if(!$res){
                return $this->renderJson(-1,'提交失败，原因是：'.PayOrderService::getErrMsg());
            }
            if( $sc == "cart" ){//如果从购物车创建订单，需要清空购物车了
                MemberCart::deleteAll([ 'member_id' => $this->current_user['id'] ]);
            }
            return $this->renderJson(200,'提交订单成功，去支付',['url' => UrlService::buildMUrl('/pay/buy/?pay_order_id='.$res['id'])]);
                
        }
    	//下订单页面
        $id = intval($this->get('id'));
        $quantity = intval($this->get('quantity'));
        $sc = $this->get("sc","product");//sc来源1直接下单2购物车
        $reback_url = UrlService::buildMUrl('/product');
        $books = [];
        $total_price = 0 ;
        if($sc == 'cart'){//从购物车下单

            $carts = MemberCart::find()->where( ['member_id' => $this->current_user['id']] )->asArray()->all();
            $books_info = Book::find()->where( ['id' => array_column($carts, 'book_id')] )->indexBy('id')->asArray()->all();
            foreach ($carts as $_item) {
                $books[] = [
                    'id' => $books_info[ $_item['book_id'] ]['id'],
                    'name' => $books_info[ $_item['book_id'] ]['name'],
                    'price' => $books_info[ $_item['book_id'] ]['price'],
                    'main_image' => $books_info[ $_item['book_id'] ]['main_image'],
                    'quantity' => $_item['quantity'],
                ];
                $total_price += $books_info[ $_item['book_id'] ]['price'] * $_item['quantity'];
            }

        }else{//直接下单
            if(!$id || !$quantity){
                $this->redirect($reback_url);
            }
            $book_info = Book::find()->where(['id' => $id,'status' => 1])->asArray()->one();
            if(!$book_info){
                $this->redirect($reback_url);
            }
            $books[] = [
                'id' => $book_info['id'],
                'name' => $book_info['name'],
                'price' => $book_info['price'],
                'main_image' => $book_info['main_image'],
                'quantity' => $quantity,
            ];
            $total_price = $book_info['price'] * $quantity;
        }
        //获取收货地址
        $address_info = MemberAddress::find()->select(['id','area_id','is_default','address'])->where(['member_id' => $this->current_user['id'],'status' => 1])->orderBy(['is_default' => SORT_DESC,'id' => SORT_DESC])->asArray()->all();
        
        $citys = City::find()->select(['id','province','city','area'])->where(['id' => array_column($address_info, 'area_id')])->indexBy('id')->asArray()->all();
        foreach ($address_info as $k => $v) {
            $city_info = $citys[ $v['area_id'] ];
            $address_info[$k]['really_address'] = $city_info['province'].$city_info['city'].$city_info['area'].$v['address'];
        }
    	return $this->render('order',[
            'books' => $books,
            'total_price' => $total_price,
            'address_info' => $address_info,
            'sc' => $sc,
        ]);
    }
    public function actionSearch(){
        //惰性加载
        //保持查询条件
        $books = $this->getBooks();
        return $this->renderJson(200,'成功',[
            'books' => $books,
            'has_next' => ( count( $books ) == 4 )?1:0 ,
        ]);
    }
    public function actionFav(){
        //收藏功能
        $act = trim( $this->post("act") );
        $id = intval( $this->post("id",0) );
        $book_id = intval( $this->post("book_id",0) );

        if( !in_array( $act,[ "del","set" ] ) ){
            return $this->renderJson(-1,'系统错误');
        }
        //取消收藏
        if( $act == "del" ){
            $fav_info = MemberFav::find()->where( ['member_id' => $this->current_user['id'],'id' => $id ] )->one();
            if($fav_info){
                $fav_info->delete();
                return $this->renderJson(200,'操作成功');
            }
            return $this->renderJson(-1,'没找到操作的项');
        }


        if( !$book_id ){
            return $this->renderJson(-1,'没有指定书籍');
        }
        //判断有没有收藏过
        $has_faved = MemberFav::find()->where([ 'member_id' => $this->current_user['id'],'book_id' => $book_id ])->count();
        if( $has_faved ){
            return $this->renderJson(-1,'已经收藏过了');
        }

        $model_fav = new MemberFav();
        $model_fav->member_id = $this->current_user['id'];
        $model_fav->book_id = $book_id;
        $model_fav->created_time = date("Y-m-d H:i:s");
        $model_fav->save( 0 );
        return $this->renderJson( 200,"收藏成功~~" );
    }
    public function actionCart(){
        //购物车功能
        $act = trim( $this->post("act","") );
        $id = intval( $this->post("id",0) );
        $book_id = intval( $this->post("book_id",0) );
        $quantity = intval( $this->post("quantity",0) );
        $date_now = date("Y-m-d H:i:s");

        if( !in_array( $act,[ "del","set" ] ) ){
            return $this->renderJson(-1,'系统错误');
        }
        //从购物车移除
        if( $act == "del" ){
            $cart_info = MemberCart::find()->where( ['member_id' => $this->current_user['id'],'id' => $id ] )->one();
            if($cart_info){
                $cart_info->delete();
                return $this->renderJson(200,'操作成功');
            }
            return $this->renderJson(-1,'没找到操作的项');
        }


        if( !$book_id || !$quantity ){
            return $this->renderJson( -1,'书籍没有指定或数量不正确' );
        }
        //查找这本书是否存在
        $book_info = Book::findOne([ 'id' => $book_id ,'status' => 1]);
        if( !$book_info ){
            return $this->renderJson( -1,'概书本已经下架' );
        }
        //判断是否已经加入过购物车
        $cart_info = MemberCart::find()->where([ 'member_id' => $this->current_user['id'],'book_id' => $book_id ])->one();
        if( $cart_info  ){
            $model_cart = $cart_info;
        }else{
            $model_cart = new MemberCart();
            $model_cart->member_id = $this->current_user['id'];
            $model_cart->created_time = $date_now;
        }

        $model_cart->book_id = $book_id;
        $model_cart->quantity = $quantity;
        $model_cart->updated_time = $date_now;
        $model_cart->save ( 0 );

        return $this->renderJson( 200,"操作成功~~" );
    }
    public function actionOps(){
        //记录书本浏览次数
        $book_id = intval($this->post('book_id',0));
        $act = trim($this->post('act'));
        if(!$book_id || !$act){
            return $this->renderJson(-1,'系统错误');
        }
        if($act != 'view_count'){
            return $this->renderJson(-1,'行为错误');
        }
        $book_info = Book::find()->where(['id' => $book_id,'status' => 1])->one();
        if(!$book_info){
            return $this->renderJson(-1,'该书本已被移除');
        }
        $book_info->view_count += 1;
        $book_info->updated_time = date('Y-m-d H:i:s');
        $book_info->update(0);
        return $this->renderJson(200,'更新成功');
    }
    //封装获取图书信息的方法，分页和主页都要用到
    public function getBooks(){
        $kw = trim( $this->get("kw") );
        $sort_field = trim( $this->get("sort_field","default") );
        $sort = trim( $this->get("sort") );
        $sort = in_array(  $sort,['asc','desc'] )?$sort:'desc';
        $p = intval($this->get('p',1));
        if($p < 1){
            $p = 1;
        }
        $page_size = 4;
        $query = Book::find()->where([ 'status' => 1 ]);
        //按关键字查找
        if( $kw ){
            $where_name = [ 'LIKE','name','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_tags = [ 'LIKE','tags','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query->andWhere([ 'OR',$where_name,$where_tags ]);
        }
        //按不同的方式排序
        switch ( $sort_field ){
            case "view_count":
            case "month_count":
            case "price":
                $query->orderBy( [  $sort_field => ( $sort == "asc")?SORT_ASC:SORT_DESC,'id' => SORT_DESC ] );
                break;
            default:
                $query->orderBy([ 'id' => SORT_DESC ]);
                break;
        }

        return $query->offset(($p - 1) * $page_size)->limit($page_size)->asArray()->all();
    }
    

}
