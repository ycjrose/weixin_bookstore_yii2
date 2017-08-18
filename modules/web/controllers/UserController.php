<?php 

namespace app\modules\web\controllers; 

use app\modules\web\common\BaseController;
use app\models\User;
use app\common\services\UrlService; 

class UserController extends BaseController{    

    public function actionLogin(){
        //登陆页面
    	if (\Yii::$app->request->isPost) {
            //登录逻辑处理
            $login_name = trim($this->post('login_name'));
            $login_pwd = trim($this->post('login_pwd'));
            if(!$login_name || !$login_pwd){
                return $this->renderJson(-1,'输入正确的用户名和密码');
            }
            //从数据库获取用户信息
            $user_info = User::find()->where(['login_name' => $login_name])->one();
            if(!$user_info){
                return $this->renderJson(-1,'用户名不存在');
            }
            //验证密码
            //密码加密 = md5(login_pwd + md5(login_salt))
            if(!$user_info->verifyPwd($login_pwd)){
                return $this->renderJson(-1,'密码错误');
            }
            //保存账号登录的cookie = $auth_token + '#' + uid
            $this->setLoginStatus($user_info);
            return $this->renderJson(200,'登陆成功');

        }
        //如果已经登录，跳转到后台
        if($this->checkLoginStatus()){
            return $this->redirect(UrlService::buildWebUrl('/dashboard'));
        }
        //展示页面
        $this->layout = 'login';
        return $this->render('login');
    }
    public function actionEdit(){
    	//编辑当前登陆人信息的页面
        if(\Yii::$app->request->isPost){
            //修改信息提交处理
            $all_post = [
                'nickname' => trim($this->post('nickname')),
                'avatar' => trim($this->post('avatar')),
                'email' => trim($this->post('email')),
                'updated_time' => date('Y:m:d H:i:s'),
            ];

            if(!$all_post['nickname'] || !$all_post['email']){
                return $this->renderJson(-1,'邮箱或名字不能为空');
            }
            if(!preg_match('/\w+[@]{1}\w+[.]\w+/', $all_post['email'])){
                return $this->renderJson(-1,'邮箱格式错误');
            }
            $user_info = $this->current_user;
            $user_info->setAttributes($all_post);
            $user_info->update(0);
            return $this->renderJson(200,'更新成功');
        }
        return $this->render('edit',['user_info' => $this->current_user]);
    }
    public function actionResetPwd(){
    	//重置当前登陆人密码的页面
        if(\Yii::$app->request->isPost){
            //修改密码提交处理
            $old_pwd = trim($this->post('old_pwd'));
            $new_pwd = trim($this->post('new_pwd'));
            if(!$old_pwd){
               return $this->renderJson(-1,'旧密码不能为空');
            }
            //验证旧密码
            $user_info = $this->current_user;
            if(!$user_info->verifyPwd($old_pwd)){
                return $this->renderJson(-1,'旧密码输入错误');
            }
            //验证新密码合法性
            if(mb_strlen($new_pwd,'utf-8') < 6){
                return $this->renderJson(-1,'新密码不能少于6位');
            }
            //更改的密码插入数据库
            $user_info->setPwd($new_pwd);
            //设置登录态
            $this->setLoginStatus($user_info);
            return $this->renderJson(200,'更改密码成功');
        }
        return $this->render('reset_pwd',['user_info' => $this->current_user]);
    }
    public function actionLogout(){
        $this->removeLoginStatus();
        return $this->redirect(UrlService::buildWebUrl('/user/login'));
    }
}
