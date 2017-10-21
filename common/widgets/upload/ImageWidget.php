<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:15
 */

namespace common\widgets\upload;

/**
 * Class ImageWidget
 * @package common\widgets\upload
 * @author 姜海强 <jhq0113@163.com>
 */
class ImageWidget extends IUploadWidget
{
    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $_defaultOptions=[
        'uploadUrl'         =>  '/up/upload',
        'fileType'          =>  'image',
        'allowExtension'    =>  ['png','jpg','jpeg','gif'],
        'maxCount'          =>  5
    ];
}