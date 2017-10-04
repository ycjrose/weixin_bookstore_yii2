<?php

namespace app\modules\web\controllers;

use app\common\services\QueueListService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\ContactService;
use app\models\book\Book;
use app\models\City;
use app\models\member\Member;
use app\models\member\MemberAddress;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\modules\web\common\BaseController;

class FinanceController extends BaseController{
    
    public function actionIndex(){
    	//订单列表
        //搜索功能
        $status = intval($this->get('status',ContactService::$status_default));
        $p = intval($this->get('p',1));

        $query = PayOrder::find();
        //按状态搜索
        if($status != ContactService::$status_default){
            $query->andWhere(['status' => $status]);
        }
        //分页操作
        $page_size = 10;//每页多少条记录
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);

        $orders = $query->offset(($p - 1) * $page_size)->limit($page_size)->orderBy(['id' => SORT_DESC])->asArray()->all(); 
        $pay_items = PayOrderItem::find()->where(['pay_order_id' => array_column($orders, 'id')])->asArray()->all();
        $books = Book::find()->where(['id' => array_column($pay_items, 'target_id')])->indexBy('id')->asArray()->all();
        //按订单号进行分类
        $items = [];
        foreach($pay_items as $_item){
            $book_info = $books[ $_item['target_id'] ];
            //如果还没有声明，则必须声明
            if(!isset($items[ $_item['pay_order_id'] ])){
                $items[ $_item['pay_order_id'] ] = [];
            }
            $items[ $_item['pay_order_id'] ][] = [
                'book_name' => $book_info['name'],
                'quantity' => $_item['quantity'],
            ];
        }

        foreach ($orders as $k => $v) {
            $orders[$k]['pay_items'] = $items[ $v['id'] ];
        }
        return $this->render('index',[
            'pay_status' => ContactService::$pay_status,
            'orders' => $orders,
            'search_conditions' => [
                'status' => $status,
                'mix_kw' => '',
            ],
            'pages' => [
                'page_size' => $page_size,
                'page_count' => $page_count,
                'page_total' => $page_total,
                'p' => $p
            ],
        ]);
    }
    public function actionAccount(){
    	//财务流水
        $p = intval($this->get('p',1));

        $query = PayOrder::find()->where(['status' => 1]);
        $total_price = $query->sum('pay_price');
        //分页操作
        $page_size = 10;//每页多少条记录
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);

        $orders = $query->offset(($p - 1) * $page_size)->limit($page_size)->orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->render('account',[
            'orders' => $orders,
            'total_price' => sprintf('%.2f',$total_price),
            'search_conditions' => [
                'status' => '',
                'mix_kw' => '',
            ],
            'pages' => [
                'page_size' => $page_size,
                'page_count' => $page_count,
                'page_total' => $page_total,
                'p' => $p
            ],
        ]);
    }
    public function actionPay_info(){
    	//订单详情
        $id = intval( $this->get("id",0) );
        $reback_url = UrlService::buildWebUrl("/finance/index");
        if( !$id ){
            return $this->redirect( $reback_url );
        }

        $order_info = PayOrder::find()->where([ 'id' => $id ])->asArray()->one();
        if( !$order_info ){
            return $this->redirect( $reback_url );
        }
        //组装所需要的数据
        $pay_items = PayOrderItem::find()->where(['pay_order_id' => $id])->asArray()->all();
        $books = Book::find()->where(['id' => array_column($pay_items, 'target_id')])->indexBy('id')->asArray()->all();
        //组装完整的item数据
        foreach ($pay_items as $k => $v) {

            $pay_items[$k]['book_name'] = $books[ $v['target_id'] ]['name'];

        }
        $member = Member::find()->where(['id' => $order_info['member_id']])->one();
        //组装完整的地址信息
        $member_address = MemberAddress::find()->where([ 'id' => $order_info['express_address_id'] ])->one();
        $city_info = City::find(['province','city','area'])->where([ 'id' => $member_address['area_id'] ])->one();
        $info = $city_info['province'].$city_info['city'].$city_info['area'].$member_address['address'];

        

        $order_info['pay_items'] = $pay_items;
        $order_info['member_name'] = $member['nickname'];
        $order_info['member_mobile'] = $member['mobile'];
        $order_info['member_address'] = $info.'('.$member_address['nickname'].')'.$member_address['mobile'];

        return $this->render('pay_info',[
            'order_info' => $order_info,

        ]);
    }
    //快递单发货的操作
    public function actionExpress(){
        $id = intval( $this->post("id",0) );
        $express_info = trim( $this->post("express_info",0 ) );
        if( !$id ){
            return $this->renderJson(-1,'id不存在');
        }

        if( mb_strlen( $express_info,"utf-8" ) < 3 ){
            return $this->renderJson(-1,'请输入符合要求的快递信息~~');
        }

        $order_info = PayOrder::find()->where([ 'id' => $id ])->one();
        if( !$order_info ){
            return $this->renderJson(-1,'找不到订单信息');
        }

        $order_info->express_info = $express_info;
        $order_info->express_status = -6;
        $order_info->updated_time = date("Y-m-d H:i:s");
        if( $order_info->update( 0 ) ){
            //发货之后要发通知
            $data = [
                'member_id' => $order_info['member_id'],
                'order_id' => $id,
                'time' => microtime(),
            ];
            QueueListService::addQueue( "express", $data);
        }
        return $this->renderJson(200,"操作成功~~");
    }
}
