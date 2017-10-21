<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:06
 */

namespace common\widgets\upload\assets;


use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class UploadAssets
 * @package common\widgets\upload\assets
 * @author 姜海强 <jhq0113@163.com>
 */
class UploadAssets extends AssetBundle
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
    public $css=[
        'css/bootstrap-upload.css'
    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $js=[
        ['js/bootstrap-upload.js']
    ];
}