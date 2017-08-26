<?php
/**
 * Created by PhpStorm.
 * User: 康华茹 <kanghuaru@zhibo.tv>
 * Date: 2017/08/04
 * Time: 18:24
 */

namespace backend\assets;

use yii\web\View;

class UEditorAssets extends AssetBundle
{
    public $css = [
        ['js/ext/ueditor/themes/default/css/ueditor.css']
    ];
    public $js = [
        ['js/ext/ueditor/ueditor.config.js','position'=>View::POS_HEAD],
        ['js/ext/ueditor/ueditor.all.min.js','position'=>View::POS_HEAD],
        ['js/ext/ueditor/lang/zh-cn/zh-cn.js','position'=>View::POS_HEAD]
    ];
}