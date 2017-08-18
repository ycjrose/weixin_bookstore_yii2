<?php
use app\common\services\UrlService;
use app\common\services\StaticService;
StaticService::includeAppJs('/js/web/user/edit.js',app\assets\WebAsset::className());
?>

<?=\Yii::$app->view->renderFile('@app/modules/web/views/user/tab_common_user.php',['current' => 'edit']);?>

<div class="row m-t  user_edit_wrap">  
    <div class="col-lg-12">
        <h2 class="text-center">账号信息编辑</h2>
        <div class="form-horizontal m-t m-b" id="weixin-form">
            <div class="form-group">
                <label class="col-lg-2 control-label">手机:</label>
                <div class="col-lg-10">
                    <input type="text" name="mobile" class="form-control" placeholder="请输入手机~~"  readonly value="<?=$user_info['mobile'];?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label">姓名:</label>
                <div class="col-lg-10">
                    <input type="text" name="nickname" class="form-control" placeholder="请输入姓名~~" value="<?=$user_info['nickname'];?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-lg-2 control-label">个人头像:</label>
                <div class="col-lg-10">
                    <form class="upload_pic_wrap" target="upload_file" enctype="multipart/form-data" method="POST" action="<?=UrlService::buildWebUrl('/upload/pic');?>">
                        <div class="upload_wrap pull-left">
                            <i class="fa fa-upload fa-2x"></i>
                            <input type="hidden" name="bucket" value="avatar" />
                            <input type="file" name="pic" >
                        </div>
                        <?php if($user_info['avatar']):?>
                        <span class="pic-each">
                            <img src="<?=UrlService::buildPicUrl('avatar',$user_info['avatar']);?>">
                            <input type="hidden" name="avatar" value="<?=$user_info['avatar'];?>" />
                            <span class="fa fa-times-circle del del_image" ><i></i></span>
                        </span>
                        <?php endif;?>
                    </form>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label">邮箱:</label>
                <div class="col-lg-10">
                    <input type="text" name="email" class="form-control" placeholder="请输入邮箱~~" value="<?=$user_info['email'];?>">
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button class="btn btn-w-m btn-outline btn-primary " id="button-submit">保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'save_url':'<?=UrlService::buildWebUrl('/user/edit');?>',
        'jump_url':'<?=UrlService::buildWebUrl('/user/edit');?>',
    }
</script>
<iframe class="hide" name="upload_file"></iframe>