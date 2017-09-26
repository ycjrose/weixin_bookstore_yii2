<?php
namespace app\common\services\book;
use app\common\services\BaseService;
use app\models\book\Book;
use app\models\book\BookStockChangeLog;
use app\models\book\BookSaleChangeLog;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
/**
* 图书服务类
*/
class BookService extends BaseService{
	//插入库存变更情况
	public static function setStockChange($book_id,$unit,$note = ''){
		date_default_timezone_set("PRC");
		$book_info = Book::find()->where(['id' => $book_id])->asArray()->one();
		if(!$book_info){
			return false;
		}
		$model_stock = new BookStockChangeLog();
		$model_stock->book_id = $book_id;
		$model_stock->unit = $unit;
		$model_stock->total_stock = $book_info['stock'];
		$model_stock->note = $note;
		$model_stock->created_time = date('Y-m-d H:i:s');
		return $model_stock->save( 0 );
	}
	//插入售卖情况
	public static function setSaleChange($pay_item_id){
		$pay_item_info = PayOrderItem::findOne(['id' => $pay_item_id]);
		if(!$pay_item_info){
			return false;
		}
		$order_info = PayOrder::findOne(['id' => $pay_item_info['pay_order_id']]);
		if(!$order_info){
			return false;
		}
		date_default_timezone_set("PRC");
		$model_sale = new BookSaleChangeLog();
		$model_sale->book_id = $pay_item_info['target_id'];
		$model_sale->quantity = $pay_item_info['quantity'];
		$model_sale->price = $pay_item_info['price'];
		$model_sale->member_id = $pay_item_info['member_id'];
		$model_sale->created_time = date('Y-m-d H:i:s');
		return $model_sale->save(0);

	}
}