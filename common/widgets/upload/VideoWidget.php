<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:16
 */

namespace common\widgets\upload;

/**
 * Class VideoWidget
 * @package common\widgets\upload
 * @author 姜海强 <jhq0113@163.com>
 */
class VideoWidget extends IUploadWidget
{
    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $_defaultOptions=[
        'uploadUrl'         =>  '/up/upload',
        'fileType'          =>  'video',
        'allowExtension'    =>  ['mp4'],
        'maxCount'          =>  1
    ];
}