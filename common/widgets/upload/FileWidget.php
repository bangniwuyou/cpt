<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:18
 */

namespace common\widgets\upload;

/**
 * Class FileWidget
 * @package common\widgets\upload
 * @author 姜海强 <jhq0113@163.com>
 */
class FileWidget extends IUploadWidget
{
    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $_defaultOptions=[
        'uploadUrl'         =>  '/up/upload',
        'fileType'          =>  'file',
        'allowExtension'    =>  ['xls','xlsx','pdf','doc','zip'],
        'maxCount'          =>  1
    ];
}