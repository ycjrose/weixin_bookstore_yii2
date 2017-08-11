<?php

namespace app\assets;

use yii\web\AssetBundle;

class WebAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function registerAssetFiles($view){
    	$this->css = [
	        'css/web/bootstrap.min.css',
	        'font-awesome/css/font-awesome.css',
	        'css/web/style.css?ver='.RELEASE_VERSION,
    	];
    	$this->js = [
	    	'plugins/jquery-2.1.1.js',
	    	'js/web/bootstrap.min.js',
	    	'js/web/common.js?ver='.RELEASE_VERSION,
    	];
    	parent::registerAssetFiles($view);
    }

}
