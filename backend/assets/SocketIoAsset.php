<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/24
 * Time: 17:03
 */

namespace backend\assets;

/**
 * Class SocketIoAsset
 * @package backend\assets
 * @author 姜海强 <jhq0113@163.com>
 */
class SocketIoAsset extends AssetBundle
{

    public $js = [
        ['js/ext/json2.min.js'],
        ['js/ext/socket.io.js'],
    ];
}