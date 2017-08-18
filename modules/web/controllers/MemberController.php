<?php

namespace app\modules\web\controllers;
 
use app\modules\web\common\BaseController;

use app\models\member\Member;

use app\common\services\ContactService;

use app\common\services\UrlService;

class MemberController extends BaseController{

    public function actionIndex(){
    	//会员列表页
        //搜索功能
        $status = intval($this->get('status',ContactService::$status_default));
        $mix_kw = trim($this->get('mix_kw'));
        $p = intval($this->get('p'));
        $query = Member::find();
        //按状态搜索
        if($status != ContactService::$status_default){
            $query->andWhere(['status' => $status]);
        }
        //关键字模糊搜索
        if($mix_kw){
            $where_nickname = ['LIKE','nickname','%'.$mix_kw.'%',false];
            $where_mobile = ['LIKE','mobile','%'.$mix_kw.'%',false];
            $query->andWhere(['OR',$where_nickname,$where_mobile]);
        }

        //分页操作
        $page_size = 10;//每页多少条记录
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);

        $members = $query->offset(($p - 1) * $page_size)
        ->limit($page_size)->orderBy(['id' => SORT_DESC])->asArray()->all(); 
        return $this->render('index',[
            'members' => $members,
            'status' => ContactService::$status,
            'search_conditions' => [
                'status' => $status,
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
    public function actionInfo(){
    	//会员详情页
        $id = intval($this->get('id'));
        if(!$id){
            return $this->redirect(UrlService::buildWebUrl('/member'));
        }
        $member_info = Member::find()->where(['id' => $id])->asArray()->one();
        return $this->render('info',['member_info' => $member_info]);
    }
    public function actionSet(){
    	//会员信息的编辑或添加
        if(\Yii::$app->request->isPost){
            //提交表单操作
            $all_post = [
                'nickname' => trim($this->post('nickname')),
                'mobile' => trim($this->post('mobile')),
                'id' => intval($this->post('id')),
            ];

            if(!$all_post['nickname']){
                return $this->renderJson(-1,'会员名不能为空');
            }
            if(!$all_post['mobile']){
                return $this->renderJson(-1,'手机号不能为空');
            }

            //通过id是否有数值来判断是更新还是新增
            if($all_post['id']){
                //更新
                $member_info = Member::find()->where(['id' => $all_post['id']])->one();
                unset($all_post['id']);
                $all_post['updated_time'] = date('Y-m-d H:i:s');
                $member_info->setAttributes($all_post);
                $member_info->update(0);
                return $this->renderJson(200,'更新成功');
            }
            //插入
            //判断会员名是否重复
            $member_info = Member::find()
            ->where(['nickname' => $all_post['nickname']])->asArray()->one();
            if($all_post['nickname'] == $member_info['nickname']){
                return $this->renderJson(-1,'会员名已存在');
            }
            $member = new Member();
            $all_post['created_time'] = date('Y-m-d H:i:s');
            $member->setAttributes($all_post);
            $member->save(0);
            return $this->renderJson(200,'新增成功');

        }
        //会员编辑页面展示
        if($id = $this->get('id')){
            $member_info = Member::find()->where(['id' => $id])->asArray()->one();
            return $this->render('set',[
                'member_info' => $member_info,
            ]); 
        }
        //会员添加页面展示
        return $this->render('set',['member_info' => ''] ); 
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
        $member_info = Member::find()->where(['id' => $id])->one();
        switch ($action) {
            case 'remove':
                $member_info->status = 0;
                break;
            case 'recover':
                $member_info->status = 1;
                break; 
        }
        $member_info->updated_time = date('Y-m-d H:i:s');
        $member_info->update(0);
        return $this->renderJson(200,'操作成功');

    }
    public function actionComment(){
        //会员的评论列表
        return $this->render('comment');
    }
}
