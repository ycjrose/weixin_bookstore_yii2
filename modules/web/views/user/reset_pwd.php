<?php
use app\common\services\UrlService;
use app\common\services\StaticService;
StaticService::includeAppJs('/js/web/user/reset_pwd.js',app\assets\WebAsset::className());
?>

<?=\Yii::$app->view->renderFile('@app/modules/web/views/user/tab_common_user.php',['current' => 'pwd']);?>

<div class="row m-t  user_reset_pwd_wrap">
    <div class="col-lg-12">
        <h2 class="text-center">修改密码</h2>
        <div class="form-horizontal m-t m-b" id="weixin-form">
            <div class="form-group">
                <label class="col-lg-2 control-label">账号:</label>
                <div class="col-lg-10">
                    <label class="control-label"><?=$user_info['nickname']?></label>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-lg-2 control-label">手机:</label>
                <div class="col-lg-10">
                    <label class="control-label"><?=$user_info['mobile']?></label>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label">原密码:</label>
                <div class="col-lg-10">
                    <input type="password" name="old_pwd" class="form-control"  value="">
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label">新密码:</label>
                <div class="col-lg-10">
                    <input type="password" name="new_pwd" class="form-control"  value="">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button class="btn btn-w-m btn-outline btn-primary" id="button-submit">保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'save_url':'<?=UrlService::buildWebUrl('/user/reset-pwd');?>',
        'jump_url':'<?=UrlService::buildWebUrl('/user/reset-pwd');?>',
    }
</script>
