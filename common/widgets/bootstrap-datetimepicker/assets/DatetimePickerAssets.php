<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/9
 * Time: 17:05
 */

namespace bootstrap_datetime_picker\assets;


use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class DatetimePickerAssets
 * @package bootstrap_datetime_picker\assets
 * @author 姜海强 <jhq0113@163.com>
 */
class DatetimePickerAssets extends AssetBundle
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
        'bootstrap-datetimepicker.min.js',
    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $css=[
        'css/bootstrap-datetimepicker.min.css',
    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**处理datetimePicker初始化参数并得到加载脚本
     * @param View   $view
     * @param string $id
     * @param array  $options
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function dealOptionsAndGetScript(View $view,$id,$options)
    {
        //注册语言js
        $langJsFile = $view->getAssetManager()->getAssetUrl($this, 'locales/bootstrap-datetimepicker.'.$options['language'].'.js');
        $view->registerJsFile($langJsFile,[
            'depends'=>[
                DatetimePickerAssets::class
            ]
        ]);

        return '$(\'#' . $id .'\').datetimepicker('.json_encode($options).');';
    }
}