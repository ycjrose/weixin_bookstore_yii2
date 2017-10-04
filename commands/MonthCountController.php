<?php 
namespace app\commands;
use app\commands\BaseController;
use app\models\book\Book;
use app\models\book\BookSaleChangeLog;

/**
* 每个月1号从书籍销售表更新月销售量
*/
class MonthCountController extends BaseController{

	public function actionIndex(){
		date_default_timezone_set('PRC');
		$books = Book::find()->select(['id'])->where(['status' => 1])->asArray()->all();
		foreach ($books as $value) {
			if( !$this->addBookCount($value['id']) ){
				echo '处理失败'.$value['id'];
			} 
		}
		echo '处理销售量结束';exit;
	}
	//根据id更新月销售量
	private function addBookCount($book_id){
		$book_info = Book::findOne(['id' => $book_id,'status' => 1]);
		if(!$book_info){
			return false;
		}
		$sale_list = BookSaleChangeLog::find()->where(['>=','created_time',date('Y-m-d H:i:s',time() - 60*60*24*30 ) ])->andWhere( ['book_id' => $book_info['id'] ] )->asArray()->all();

		$sum = 0;
		foreach ($sale_list as $value) {
			$sum += $value['quantity'];
		}
		$book_info->month_count = $sum;
		$book_info->updated_time = date('Y-m-d H:i:s');
		$book_info->update(0);
		return true;
	}
}