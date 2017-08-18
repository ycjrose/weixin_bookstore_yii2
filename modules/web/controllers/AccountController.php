<?php

namespace app\modules\web\controllers;
 
use app\modules\web\common\BaseController; 

use app\models\User;

use app\models\log\AppAccessLog;

use app\common\services\UrlService; 

use app\common\services\ContactService;

class AccountController extends BaseController{

    public function actionIndex(){
    	//账户列表
        //搜索功能
        $status_get = intval($this->get('status',ContactService::$status_default));
        $mix_kw = trim($this->get('mix_kw'));
        $p = intval($this->get('p',1));

        $query = User::find();
        //状态搜索
        if($status_get != ContactService::$status_default){
            $query->andWhere(['status' => $status_get]);
        }
        //关键字模糊搜索
        if($mix_kw){
            $where_nickname = ['LIKE','nickname','%'.$mix_kw.'%',false];
            $where_mobile = ['LIKE','mobile','%'.$mix_kw.'%',false];
            $query->andWhere(['OR',$where_nickname,$where_mobile]);
        }
        //分页操作
        $page_size = 10;
        $page_count = $query->count();
        $page_total = ceil($page_count / $page_size);



        $list = $query->offset(($p - 1) * $page_size)->limit($page_size)->orderBy(['uid' => SORT_DESC])->all();

        //获取当前登录人信息，当前登录人不能再本页操作自己信息
        $current_user = $this->current_user;
        return $this->render('index',[
            'list' => $list,
            'status' => ContactService::$status,
            'current_uid' => $current_user['uid'],
            'search_conditions' => [
                'status' => $status_get,
                'mix_kw' => $mix_kw,
                
            ],
            'pages' => [
                'page_size' => $page_size,
                'page_count' => $page_count,
                'page_total' => $page_total,
                'p' => $p,
            ], 

        ]);
    }
    public function actionSet(){
    	//用户编辑或添加
        if(\Yii::$app->request->isPost){
            //提交表单操作
            $all_post = [
                'nickname' => trim($this->post('nickname')),
                'avatar' => trim($this->post('avatar')),
                'mobile' => trim($this->post('mobile')),
                'email' => trim($this->post('email')),
                'login_name' => trim($this->post('nickname')),
                'login_pwd' => trim($this->post('login_pwd')),
                'uid' => intval($this->post('uid')),
            ];

            if(!$all_post['nickname']){
                return $this->renderJson(-1,'姓名不能为空');
            }

            if(!preg_match('/\w+[@]{1}\w+[.]\w+/',$all_post['email'])){
                return $this->renderJson(-1,'邮箱格式不正确');
            }
            if(!$all_post['login_name']){
                return $this->renderJson(-1,'登录名不能为空');
            }
            if(!$all_post['login_pwd']){
                return $this->renderJson(-1,'密码不能为空');
            }

            //判断是更新还是新插入（是否有$all_post['uid']）
            if($uid = $this->post('uid')){
                //更新
                $user_info = User::find()->where(['uid' => $uid])->one();
                unset($all_post['uid']);
                $all_post['updated_time'] = date('Y:m:d H:i:s');
                //判断密码有没有修改
                if($all_post['login_pwd'] != ContactService::$default_pwd){
                    $all_post['login_pwd'] = $user_info->getSaltPwd($all_post['login_pwd']);
                }else{
                    unset($all_post['login_pwd']);
                }
                
                $user_info->setAttributes($all_post);
                $user_info->update(0);
                return $this->renderJson(200,'更新成功');
            }
            //插入
            //判断用户名是否存在
            $save_user = User::find()->where(['login_name' => $all_post['login_name']])->asArray()->one();
            if($all_post['login_name'] == $save_user['login_name']){
                return $this->renderJson(-1,'用户名已存在');
            }

            $user = new User();
            $user->setSalt();
            $all_post['created_time'] = date('Y:m:d H:i:s');
            $all_post['login_pwd'] = $user->getSaltPwd($all_post['login_pwd']);
            $user->setAttributes($all_post);
            $user->save(0);
            return $this->renderJson(200,'新增成功');

        }
        //用户编辑页面展示
        if($uid = $this->get('id')){
            $user_info = User::find()->where(['uid' => $uid])->asArray()->one();
            return $this->render('set',[
                'user_info' => $user_info,
            ]); 
        }
        //用户添加页面展示
        return $this->render('set',['user_info' => ''] ); 
    }
    public function actionInfo(){
    	//账户详情
        $uid = $this->get('id',0);
        $reback_url = UrlService::buildWebUrl('/account');
        if(!$uid){
            return $this->redirect($reback_url);
        }
        $user_info = User::find()->where(['uid' => $uid])->asArray()->one();
        if(!$user_info){
            return $this->redirect($reback_url);
        }

        //账号的浏览日志
        $access_list = AppAccessLog::find()->where(['uid' => $user_info['uid']])
                        ->orderBy(['id' => SORT_DESC])->limit(10)->asArray()->all();
        return $this->render('info',[
            'user_info' => $user_info,
            'access_list' => $access_list,
        ]);
    }
    public function actionOps(){
        //删除或恢复操作
        if(\Yii::$app->request->isPost){
            $action = trim($this->post('action'));
            $uid = intval($this->post('uid',0));
            
            if(!in_array($action,['remove','recover'])){
                return $this->renderJson(-1,'操作有误');
            }

            $user_info = User::find()->where(['uid' => $uid])->one();
            if(!$user_info){
                return $this->renderJson(-1,'指定账号不存在');
            }

            switch ($action) {
                case 'remove':
                    $user_info->status = 0;
                    break;
                
                case 'recover':
                    $user_info->status = 1;
                    break;
            }
            $user_info->updated_time = date('Y:m:d H:i:s');
            $user_info->update(0);
            return $this->renderJson(200,'操作成功！');

        }
    }
}
