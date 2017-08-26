<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/7
 * Time: 16:45
 */

namespace backend\assets;

use yii\web\View;

class HighChartsAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        ['js/ext/highcharts/highcharts.js','position'=>View::POS_HEAD]
    ];
}