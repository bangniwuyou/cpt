<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/8
 * Time: 14:41
 */

namespace summer\assets;


use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class SummerAssets
 * @package summer\assets
 * @author 姜海强 <jhq0113@163.com>
 */
class SummerAssets extends AssetBundle
{
    /**
     * @var string
     * @author 姜海强 <jhq0113@163.com>
     */
    public $sourcePath = __DIR__.'/dist';

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $js = [
        'summernote.min.js',
        'jquery-summernote.js'
    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $css=[
        'summernote.css'
    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**处理Summernote初始化参数并得到加载脚本
     * @param View   $view
     * @param string $id
     * @param array  $options
     * @param string $uploadUrl
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function dealOptionsAndGetScript(View $view,$id,$options,$uploadUrl)
    {
        //注册语言js
        $langJsFile = $view->getAssetManager()->getAssetUrl($this, 'lang/summernote-'.$options['lang'].'.js');
        $view->registerJsFile($langJsFile,[
            'depends'=>[
                SummerAssets::class
            ]
        ]);

        return '$(\'#' . $id .'\').summernote($.addUploadImgOption('.json_encode($options).',"'.$uploadUrl.'","'.$id.'"));';
    }

}