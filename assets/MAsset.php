<?php

namespace app\assets;
 
use yii\web\AssetBundle;

class MAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function registerAssetFiles($view){
    	$this->css = [
	        'font-awesome/css/font-awesome.css',
	        'css/m/css_style.css',
	        'css/m/app.css?ver='.RELEASE_VERSION,
    	];
    	$this->js = [
	    	'plugins/jquery-2.1.1.js',
	    	'js/m/TouchSlide.1.1.js',
            'plugins/layer/layer.js',
            'plugins/dialog.js',
	    	'js/m/common.js?ver='.RELEASE_VERSION,
    	];
    	parent::registerAssetFiles($view);
    }

}
