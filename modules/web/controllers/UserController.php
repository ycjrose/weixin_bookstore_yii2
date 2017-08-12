<?php 

namespace app\modules\web\controllers; 

use app\modules\web\common\BaseController;
use app\models\User;
use app\common\services\UrlService;

class UserController extends BaseController{

    public function actionLogin(){
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
            $auth_pwd = md5($login_pwd.md5($user_info['login_salt']));
            if($auth_pwd != $user_info['login_pwd']){
                return $this->renderJson(-1,'密码错误');
            }
            //保存账号登录的cookie = $auth_token + '#' + uid
            $this->setLoginStatus($user_info);
            return $this->renderJson(200,'登陆成功');

        }
        //登陆页面
        if($this->checkLoginStatus()){
            return $this->redirect(UrlService::buildWebUrl('/dashboard'));
        }
        $this->layout = 'login';
        return $this->render('login');
    }
    public function actionEdit(){
    	//编辑当前登陆人信息的页面
        return $this->render('edit');
    }
    public function actionResetPwd(){
    	//重置当前登陆人密码的页面
        return $this->render('reset_pwd');
    }
    public function actionLogout(){
        $this->removeLoginStatus();
        return $this->redirect(UrlService::buildWebUrl('/user/login'));
    }
}
