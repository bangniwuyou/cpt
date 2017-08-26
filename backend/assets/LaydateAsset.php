<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/1
 * Time: 17:22
 */

namespace backend\assets;

use yii\web\View;

class LaydateAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        ['js/ext/laydate/laydate.js','position'=>View::POS_HEAD]
    ];
}