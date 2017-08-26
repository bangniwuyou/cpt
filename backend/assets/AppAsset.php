<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $css = [
        'css/site.css',
    ];
    public $js = [
        ['js/jquery-com.js','position'=>View::POS_HEAD],
        ['js/ext/ajaxfileupload.js','position'=>View::POS_BEGIN]
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\LaydateAsset',
        'backend\assets\HighChartsAsset',
        'backend\assets\UEditorAssets',
        'backend\assets\Md5Asset',
    ];
}
