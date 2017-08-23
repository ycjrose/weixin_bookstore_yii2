<?php

namespace app\modules\m\controllers;

use app\modules\m\common\BaseController;

use app\common\components\HttpClient;

use app\common\services\UrlService;

use app\models\member\Member;

use app\models\member\OauthMemberBind;

/** 
* 微信授权登录
*/
class OauthController extends BaseController{
	
	public function actionLogin(){
		$scope = $this->get('scope','snsapi_base');
		$appid = \Yii::$app->params['weixin']['appid'];
		$redirect_uri = \Yii::$app->params['domain']['windows'].UrlService::buildMUrl('/oauth/callback');
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=#wechat_redirect";
		return $this->redirect($url);
	}
	public function actionCallback(){
		$code = $this->get('code');
		if(!$code){
			return $this->goHome();
		}
		//通过code获取网页授权的access_token
		$appid = \Yii::$app->params['weixin']['appid'];
		$sk = \Yii::$app->params['weixin']['sk'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$sk}&code={$code}&grant_type=authorization_code";
		$res = HttpClient::get($url);
		$res = @json_decode($res,true);
		$res_token = isset($res['access_token']) ? $res['access_token'] : '';
		if(!$res_token){
			return $this->goHome();
		}
		$res_openid = isset($res['openid']) ? $res['openid'] : '';
		$res_scope = isset($res['scope']) ? $res['scope'] : '';

		$this->setCookie( $this->auth_cookie_current_openid,$res_openid );
		
		//刚进页面只要openid，不授权然后跳转到首页
		if($res_scope != 'snsapi_userinfo'){
			return $this->goHome();
		}
		//授权过来
		//判断是否在数据库已绑定你的openid
		$reg_bind = OauthMemberBind::find()->where([ 'openid' => $res_openid,'type' => 1 ])->one();
		if($reg_bind){
			//在进行绑定表和会员表之间的对应关系判断
			$member_info = Member::findOne( [ 'id' => $reg_bind['member_id'],'status' => 1 ] );
			if( !$member_info ){
				$reg_bind->delete();
				return $this->goHome();
			}
			//获取用户信息
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$res_token}&openid={$res_openid}&lang=zh_CN";
			$weixin_user_info = HttpClient::get($url);
			$weixin_user_info = @json_decode($weixin_user_info,true);
			//更新用户的信息（头像，用户名）
			if( $member_info['nickname'] == $member_info['mobile'] ){
				$member_info->nickname = isset( $weixin_user_info['nickname'] )?$weixin_user_info['nickname']:$member_info->nickname;
				$member_info->avatar = isset( $weixin_user_info['headimgurl'] )?$weixin_user_info['headimgurl']:$member_info->avatar;
				$member_info->update( 0 );
			}

			//设置登录态
			$this->setLoginStatus( $member_info );
		}

		//跳转到会员页面
		$this->redirect(UrlService::buildMUrl('/user'));


		
	}
	//退出登录
	public function actionLogout(){
		$this->removeLoginStatus();
		$this->removeCookie( $this->auth_cookie_current_openid );
		return $this->goHome();
	}
}