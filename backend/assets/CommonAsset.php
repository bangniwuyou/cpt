<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/7
 * Time: 14:04
 */

namespace backend\assets;

use yii\web\View;

/**
 * Class CommonAsset
 * @package backend\assets
 * @author 姜海强 <jhq0113@163.com>
 */
class CommonAsset extends AssetBundle
{
    public $js = [
        ['js/jquery-com.js','position'=>View::POS_HEAD],
    ];
}