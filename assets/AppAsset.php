<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $sourcePath = '@vendor';
    public $baseUrl = '@web';
    public $css = [
        'web/css/main.css',
        'web/css/style.css',
        'web/slick/slick.css',
        'web/slick/slick-theme.css',
    ];
    public $js = [
        'web/slick/slick.min.js',
    ];
    public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
  
}