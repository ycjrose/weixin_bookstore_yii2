<?php

namespace app\modules\web\controllers;

use app\modules\web\common\BaseController;
use app\models\book\Book;
use app\models\book\BookCat;
use app\models\book\Images;
use app\models\book\BookStockChangeLog;
use app\models\book\BookSaleChangeLog;
use app\models\member\Member;
use app\common\services\ContactService;
use app\common\services\book\BookService;
use app\common\services\UrlService;

class BookController extends BaseController{
     public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }
 
    public function actionIndex(){
    	//图书列表
        //搜索功能
        $status = intval($this->get('status',ContactService::$status_default));
        $mix_kw = trim($this->get('mix_kw'));
        $catid = intval($this->get('catid',ContactService::$status_default));
        $p = intval($this->get('p',1));
        $query = Book::find();
        //按状态搜索
        if($status != ContactService::$status_default){
            $query->andWhere(['status' => $status]);
        }
        //按分类搜索
        if($catid != ContactService::$status_default){
            $query->andWhere(['cat_id' => $catid]);
        }
        //关键字模糊搜索
        if($mix_kw){
            $where_name = ['LIKE','name','%'.$mix_kw.'%',false];
            $where_tags = ['LIKE','tags','%'.$mix_kw.'%',false];
            $query->andWhere(['OR',$where_name,$where_tags]);
        }

        //分页操作
        $page_size = 10;//每页多少条记录
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);
        //把所有分类列出来
        $cat_info = BookCat::find()->orderBy(['weight' => SORT_DESC])->asArray()->all();
        $cat = [];
        foreach ($cat_info as $value) {
            $cat[$value['id']] = $value['name'];
        }

        $books = $query->offset(($p - 1) * $page_size)
        ->limit($page_size)->orderBy(['id' => SORT_DESC])->asArray()->all(); 
        return $this->render('index',[
            'books' => $books,
            'status' => ContactService::$status,
            'cat' => $cat,
            'search_conditions' => [
                'status' => $status,
                'catid' => $catid,
                'mix_kw' => $mix_kw,
            ],
            'pages' => [
                'page_size' => $page_size,
                'page_count' => $page_count,
                'page_total' => $page_total,
                'p' => $p
            ], 
        ]);

    }
    public function actionSet(){
    	//图书编辑或添加
        if(\Yii::$app->request->isPost){
            //提交表单操作
            $all_post = [
                'cat_id' => intval($this->post('catid')),
                'name' => trim($this->post('name')),
                'price' => $this->post('price'),
                'main_image' => trim($this->post('main_image')),
                'summary' => trim($this->post('summary')), 
                'stock' => trim($this->post('stock',0)),
                'tags' => trim($this->post('tags')),
                'id' => intval($this->post('id')),
            ];

            if(!$all_post['cat_id']){
                return $this->renderJson(-1,'请选择图书分类！');
            }
            if(!$all_post['name']){
                return $this->renderJson(-1,'图书名不能为空');
            }
            if( $all_post['price'] <= 0  ){
                return $this->renderJSON(-1,"请输入符合规范的图书售卖价格~~");
            }

            if( mb_strlen( $all_post['main_image'] ,"utf-8") < 3 ){
                return $this->renderJSON(-1,"请上传封面图~~");
            }

            if( mb_strlen( $all_post['summary'],"utf-8" ) < 10 ){
                return $this->renderJSON(-1,"请输入图书描述，并不能少于10个字符".mb_strlen( $all_post['summary'],"utf-8" ));
            }

            if( $all_post['stock'] < 1 ){
                return $this->renderJSON(-1,"请输入符合规范的库存量");
            }

            if( mb_strlen( $all_post['tags'],"utf-8" ) < 1 ){
                return $this->renderJSON(-1,"请输入图书标签，便于搜索~~");
            }

            //通过id是否有数值来判断是更新还是新增
            if($all_post['id']){
                //更新
                $book_info = Book::find()->where(['id' => $all_post['id']])->one();
                $before_stock = $book_info->stock;
                unset($all_post['id']);
                $all_post['updated_time'] = date('Y-m-d H:i:s');
                $book_info->setAttributes($all_post);
                if($book_info->update(0)){
                    //库存变更
                    if($book_info->stock != $before_stock){
                        BookService::setStockChange( $book_info->id,( $book_info->stock - $before_stock ) );
                    }
                }
                
                return $this->renderJson(200,'更新成功');
            }
            //插入
            //判断书名是否重复
            $book_info = Book::find()
            ->where(['name' => $all_post['name']])->asArray()->one();
            if($all_post['name'] == $book_info['name']){
                return $this->renderJson(-1,'这本书已存在');
            }
            $book_info = new Book();
            unset($all_post['id']);
            $all_post['created_time'] = date('Y-m-d H:i:s');
            $book_info->setAttributes($all_post);
            $book_info->save(0);
            return $this->renderJson(200,'新增成功');

        }
        //图书编辑页面展示
        if($id = $this->get('id')){
            $cat_info = BookCat::find()->orderBy(['weight' => SORT_DESC])->asArray()->all();
            $cat = [];
            foreach ($cat_info as $value) {
                $cat[$value['id']] = $value['name'];
            }
            $book_info = Book::find()->where(['id' => $id])->asArray()->one();
            return $this->render('set',[
                'book_info' => $book_info,
                'cat' => $cat,
            ]); 
        }
        //添加页面显示
        $cat_info = BookCat::find()->orderBy(['weight' => SORT_DESC])->asArray()->all();
        $cat = [];
        foreach ($cat_info as $value) {
            $cat[$value['id']] = $value['name'];
        }
        return $this->render('set',['book_info' => 0,'cat' => $cat]);
    }
    public function actionOps(){
        //删除或恢复操作
        $action = trim($this->post('action'));
        $id = trim($this->post('uid'));

        if(!$action || !$id){
            return $this->renderJson(-1,'操作失败');
        }
        if(!in_array($action, ['remove','recover'])){
            return $this->renderJson(-1,'没有指定操作');
        }
        //删除还是恢复执行不同语句
        $book_info = Book::find()->where(['id' => $id])->one();
        switch ($action) {
            case 'remove':
                $book_info->status = 0;
                break;
            case 'recover':
                $book_info->status = 1;
                break; 
        }
        $book_info->updated_time = date('Y-m-d H:i:s');
        $book_info->update(0);
        return $this->renderJson(200,'操作成功');
    }
    public function actionInfo(){
    	//图书详情
        $id = intval($this->get('id',0));
        $reback_url = UrlService::buildWebUrl('/book');
        if(!$id){
            return $this->redirect($reback_url);
        }
        $book_info = Book::find()->where(['id' => $id])->asArray()->one();
        if(!$book_info){
            return $this->redirect($reback_url);
        }
        //库存变更历史
        $stock_change_list = BookStockChangeLog::find()->where(['book_id' => $id])->orderBy(['id' => SORT_DESC])->asArray()->all();
        //售卖变更历史
        $sale_change_list = BookSaleChangeLog::find()->where(['book_id' => $id])->orderBy(['id' => SORT_DESC])->asArray()->all();
        $members = Member::find()->where(['id' => array_column($sale_change_list, 'member_id')])->indexBy('id')->asArray()->all();
        //取出购买的会员的名称
        foreach ($sale_change_list as $k => $_item) {
            $sale_change_list[$k]['member_name'] = $members[$_item['member_id']]['nickname']; 
        }

        return $this->render('info',[
            'book_info' => $book_info,
            'stock_change_list' => $stock_change_list,
            'sale_change_list' => $sale_change_list,
        ]);
    }
    public function actionImages(){
        //图书图片资源
        $p = intval($this->get('p',1));
        $query = Images::find();
        //分页操作
        $page_size = 5;//每页多少条记录
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);

        $images = $query->offset(($p - 1) * $page_size)->limit($page_size)->orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->render('images',[
            'images' => $images,
            'pages' => [
                'page_size' => $page_size,
                'page_count' => $page_count,
                'page_total' => $page_total,
                'p' => $p
            ], 
        ]);
    }
    public function actionCat(){
        //分类列表
        //搜索功能
        $status = intval($this->get('status',ContactService::$status_default));
        $query = BookCat::find();
        //按状态搜索
        if($status != ContactService::$status_default){
            $query->andWhere(['status' => $status]);
        }


        $cats = $query->orderBy(['weight' => SORT_DESC])->asArray()->all(); 
        return $this->render('cat',[
            'cats' => $cats,
            'status' => ContactService::$status,
            'search_conditions' => [
                'status' => $status,
            ],

        ]);
    }
    public function actionCat_set(){
        //图书分类的编辑和添加
        //会员信息的编辑或添加
        if(\Yii::$app->request->isPost){
            //提交表单操作
            $all_post = [
                'name' => trim($this->post('name')),
                'weight' => intval($this->post('weight')),
                'id' => intval($this->post('id')),
            ];

            if(!$all_post['name']){
                return $this->renderJson(-1,'分类名不能为空');
            }
            if(!$all_post['weight']){
                return $this->renderJson(-1,'权重不能为空');
            }

            //通过id是否有数值来判断是更新还是新增
            if($all_post['id']){
                //更新
                $cat = BookCat::find()->where(['id' => $all_post['id']])->one();
                unset($all_post['id']);
                $all_post['updated_time'] = date('Y-m-d H:i:s');
                $cat->setAttributes($all_post);
                $cat->update(0);
                return $this->renderJson(200,'更新成功');
            }
            //插入
            //判断分类名是否重复
            $cat = BookCat::find()
            ->where(['name' => $all_post['name']])->asArray()->one();
            if($all_post['name'] == $cat['name']){
                return $this->renderJson(-1,'分类名已存在');
            }
            $cat = new BookCat();
            $all_post['created_time'] = date('Y-m-d H:i:s');
            $cat->setAttributes($all_post);
            $cat->save(0);
            return $this->renderJson(200,'新增成功');

        }
        //图书编辑页面展示
        if($id = $this->get('id')){
            $cat = BookCat::find()->where(['id' => $id])->asArray()->one();
            return $this->render('cat_set',[
                'cat' => $cat,
            ]); 
        }
        //图书添加页面展示
        return $this->render('cat_set',['cat' => ''] );
    }
    public function actionCat_ops(){
        //图书分类的删除和恢复
        //删除或恢复操作
        $action = trim($this->post('action'));
        $id = trim($this->post('uid'));

        if(!$action || !$id){
            return $this->renderJson(-1,'操作失败');
        }
        if(!in_array($action, ['remove','recover'])){
            return $this->renderJson(-1,'没有指定操作');
        }
        //删除还是恢复执行不同语句
        $book_info = BookCat::find()->where(['id' => $id])->one();
        switch ($action) {
            case 'remove':
                $book_info->status = 0;
                break;
            case 'recover':
                $book_info->status = 1;
                break; 
        }
        $book_info->updated_time = date('Y-m-d H:i:s');
        $book_info->update(0);
        return $this->renderJson(200,'操作成功');
    }
}
