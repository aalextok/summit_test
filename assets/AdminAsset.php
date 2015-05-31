<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
		'css/formstone.css',
		'css/style.css',
		'css/override.css',
    ];
    public $js = [
        'js/vendor/jquery-ui.min.js',
        'js/vendor/formstone.min.js',
        'js/script.js',
        'js/forms/basic.js',
        'js/forms/checkbox.js',
        'js/forms/radio.js',
        'js/forms/range.js',
        'js/forms/single.js',
        'js/forms/image.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();
		
        // resetting BootstrapAsset to not load own css files
        Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
            'css' => []
        ];
        Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapPluginAsset'] = [
            'js' => []
        ];
    }
}
