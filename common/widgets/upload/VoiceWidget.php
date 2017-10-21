<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:17
 */

namespace common\widgets\upload;


class VoiceWidget extends IUploadWidget
{
    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $_defaultOptions=[
        'uploadUrl'         =>  '/up/upload',
        'fileType'          =>  'file',
        'allowExtension'    =>  ['mp3'],
        'maxCount'          =>  5
    ];
}