<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-theme.min.css',
        'css/component.css',
        'css/site.css'
    ];
    public $js = [
        'js/modernizr.custom.js',
        'js/bootstrap.min.js',
        'js/jquery.searchinput.js',
        'js/component.js',
        'js/summit-to-sea.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function __construct(){
      
      if (
          Yii::$app->controller->module->requestedRoute != 'site/index' && 
          Yii::$app->controller->module->requestedRoute != ''
      ) {
        $this->css[] = 'css/sidebar.css';
      } else {
        $this->css[] = 'css/cover.css';
      }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
    }
}
