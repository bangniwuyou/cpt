<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/7/14
 * Time: 14:33
 */

namespace common\utils;

use common\base\IUtils;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**上传帮助类
 * Class UploadHelper
 * @package common\utils
 * @author 姜海强 <jhq0113@163.com>
 */
class UploadHelper extends IUtils
{
    /**
     * 图片
     */
    const IMG='images';

    /**
     * 视频
     */
    const VIDEO='video';

    /**
     * 音频
     */
    const VOICE='voice';

    /**
     * 文件
     */
    const FILE='file';

    /**
     * @var string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static $uploadBaseName='uploads';

    /**文件白名单
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static $fileWhiteMap=[
        'images'    => ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
        'video'     => ['flv', 'avi','mp4','m4v'],
        'voice'     => ['mp3'],
        'file'      => ['zip','apk']
    ];

    /**
     * 文件最大大小,单位M
     */
    const MAX_SIZE=10;

    /**创建目录
     * @param string  $dir  全路径目录
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function createDir($dir)
    {
        if(!file_exists($dir))
        {
            mkdir($dir);
        }
    }

    /**创建日期路径
     * @param string $forwardPath    创建日期路径前路径
     * @param string $saveUrl        url
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    protected static function createDateDir($forwardPath,$saveUrl)
    {
        $year='/'.date('Y').'/';
        $forwardPath.=$year;
        $saveUrl.=$year;

        $month=date('m').'/';
        self::createDir($forwardPath);
        $forwardPath.=$month;
        $saveUrl.=$month;
        self::createDir($forwardPath);

        $day=date('d');
        $forwardPath.=$day;
        $saveUrl.=$day;
        self::createDir($forwardPath);

        return [
            'path'=>$forwardPath,
            'url'=>$saveUrl
        ];
    }

    /**获取扩展名目录
     * @param string   $extension
     * @return bool|int|string
     * @author 姜海强 <jhq0113@163.com>
     */
    protected static function getExtensionDir($extension)
    {
        foreach (self::$fileWhiteMap as $dir=>$items)
        {
            if(in_array($extension,$items))
            {
                return $dir;
            }
        }
        return false;
    }

    /**生成随机文件名
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    protected static function getRandomName()
    {
        return date('H_i_s_').md5(uniqid());
    }

    /**字节转M
     * @param int   $size
     * @return float
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function b2M($size)
    {
        return round( ( $size / (1024*1024) ),1);
    }

    /**
     * @param string $file      文件上传实例
     * @param string $basePath  基础物理目录
     * @param string $domain    域名
     * @param string $fileType  文件类型
     * @author 姜海强 <jhq0113@163.com>
     */
    protected static function up(UploadedFile $file,$basePath,$domain='',$fileType='')
    {
        if(!$file)
        {
            return ['message'=>'请选择文件','status'=>'1001'];
        }

        //获取扩展名
        $fileExtension = $file->getExtension();

        $extensionDir=false;

        //没传文件类型，自动分配目录
        if(empty($fileType))
        {
            $extensionDir=self::getExtensionDir($fileExtension);
        }
        else
        {
            //文件类型是否在白名单中
            if(!isset(self::$fileWhiteMap[ $fileType ]))
            {
                throw new HttpException('500','参数$fileType值需为【'.implode(',',array_keys(self::$fileWhiteMap)).'】中之一');
            }
            if(in_array($fileExtension,self::$fileWhiteMap[ $fileType]))
            {
                $extensionDir=$fileType;
            }
        }

        if(!$extensionDir)
        {
            return ['message'=>'暂不支持【.'.$fileExtension.'】格式的文件上传','status'=>'1002'];
        }

        if($file->size == 0)
        {
            return ['message'=>'文件大小超过服务器设置限制'.self::MAX_SIZE.'M','status'=>'1003'];
        }

        $size=self::b2M($file->size);

        if($size > self::MAX_SIZE)
        {
            return ['message'=>'文件大小不能超过'.self::MAX_SIZE.'M','status'=>'1004'];
        }

        //创建基础目录
        self::createDir($basePath.DIRECTORY_SEPARATOR.self::$uploadBaseName);

        $savePath = $basePath.DIRECTORY_SEPARATOR.self::$uploadBaseName.DIRECTORY_SEPARATOR.$extensionDir;
        $saveUrl  = $domain.'/'.self::$uploadBaseName.'/'.$extensionDir;

        //创建文件类型目录
        self::createDir($savePath);

        //创建日期目录
        $dateInfo=self::createDateDir($savePath,$saveUrl);

        $savePath=$dateInfo['path'];
        $saveUrl=$dateInfo['url'];

        /**
         * 生成文件名
         */
        $fileName=self::getRandomName().'.'.$fileExtension;

        //保存文件
        if($file->saveAs($savePath.DIRECTORY_SEPARATOR.$fileName))
        {
            return ['message'=>$saveUrl.'/'.$fileName,'status'=>'200'];
        }

        return ['message'=>'上传失败','status'=>'1006'];
    }

    /**根据参数名获取上传文件对象
     * @param string  $name     参数名
     * @return null|UploadedFile
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function getFileByName($name)
    {
        return UploadedFile::getInstanceByName($name);
    }

    /**传图片
     * @param UploadedFile $file         上传文件对象
     * @param string       $basePath     上传基础路径
     * @param string       $domain       url域名，默认为空串
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upImg(UploadedFile $file,$basePath,$domain='')
    {
        return self::up($file,$basePath,$domain,self::IMG);
    }

    /**传视频
     * @param UploadedFile  $file       上传文件对象
     * @param string        $basePath   上传基础路径
     * @param string        $domain     url域名，默认为空串
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upVideo(UploadedFile $file,$basePath,$domain='')
    {
        return self::up($file,$basePath,$domain,self::VIDEO);
    }

    /**传音频
     * @param UploadedFile $file        上传文件对象
     * @param string       $basePath    上传基础路径
     * @param string       $domain      url域名，默认为空串
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upVoice(UploadedFile $file,$basePath,$domain='')
    {
        return self::up($file,$basePath,$domain,self::VOICE);
    }

    /**传文件
     * @param UploadedFile $file        上传文件对象
     * @param string       $basePath    上传基础路径
     * @param string       $domain      url域名，默认为空串
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upFile(UploadedFile $file,$basePath,$domain='')
    {
        return self::up($file,$basePath,$domain,self::FILE);
    }
}