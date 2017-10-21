<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/9
 * Time: 13:24
 */

namespace summer\utils;

use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Class UploadHelper
 * @package summer\utils
 * @author 姜海强 <jhq0113@163.com>
 */
class UploadHelper
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

    /**文件白名单
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static $fileWhiteMap=[
        self::IMG           => [ 'jpg', 'jpeg', 'png', 'bmp','gif'],
        self::VIDEO         => ['flv', 'avi','mp4','m4v'],
        self::VOICE         => ['mp3'],
        self::FILE          => ['zip','apk']
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

    /**b转M
     * @param int   $size
     * @return float
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function b2M($size)
    {
        return round( ( $size / (1024*1024) ),1);
    }

    /**创建日期路径
     * @param string $forwardPath    创建日期路径前路径
     * @param string $saveUrl        url
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function createDateDir($forwardPath,$saveUrl)
    {
        $year='/'.date('Y').'/';
        $forwardPath.=$year;
        $saveUrl.=$year;

        $month=date('m').'/';
        static::createDir($forwardPath);
        $forwardPath.=$month;
        $saveUrl.=$month;
        static::createDir($forwardPath);

        $day=date('d');
        $forwardPath.=$day;
        $saveUrl.=$day;
        static::createDir($forwardPath);

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
    public static function getExtensionDir($extension)
    {
        foreach (static::$fileWhiteMap as $dir=>$items)
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
    public static function getRandomName()
    {
        return date('H_i_s_').md5(uniqid());
    }

    /**创建上传目录和文件名并获取信息
     * @param string    $basePath
     * @param string    $extensionDir
     * @param string    $urlPrefix
     * @param string    $fileExtension
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function autoCreateDirAndGetUploadInfo($basePath,$extensionDir,$urlPrefix,$fileExtension)
    {
        //创建基础目录
        static::createDir($basePath.DIRECTORY_SEPARATOR);

        $savePath = $basePath.DIRECTORY_SEPARATOR.$extensionDir;
        $saveUrl  = $urlPrefix.'/'.$extensionDir;

        //创建文件类型目录
        static::createDir($savePath);

        //创建日期目录
        $uploadInfo=static::createDateDir($savePath,$saveUrl);

        $uploadInfo['fileName'] = static::getRandomName().'.'.$fileExtension;

        return $uploadInfo;
    }

    /**上传
     * @param string        $file      文件上传实例
     * @param string        $basePath  基础物理目录
     * @param string        $urlPrefix url前缀，默认为空串
     * @param string        $fileType  文件类型
     * @param callable|null $createDirAndGetUploadInfoCallBack  创建上传目录和文件名并获取信息回调函数
     * @author 姜海强 <jhq0113@163.com>
     */
    protected static function up(UploadedFile $file, $basePath, $urlPrefix='', $fileType='', callable $createDirAndGetUploadInfoCallBack=null)
    {
        if(!$file)
        {
            return ['data'=>'请选择文件','status'=>'1001'];
        }

        //获取扩展名
        $fileExtension = $file->getExtension();

        $extensionDir=false;

        //没传文件类型，自动分配目录
        if(empty($fileType))
        {
            $extensionDir=static::getExtensionDir($fileExtension);
        }
        else
        {
            //文件类型是否在白名单中
            if(!isset(static::$fileWhiteMap[ $fileType ]))
            {
                throw new HttpException('500','参数$fileType值需为【'.implode(',',array_keys(static::$fileWhiteMap)).'】中之一');
            }
            if(in_array($fileExtension,static::$fileWhiteMap[ $fileType]))
            {
                $extensionDir=$fileType;
            }
        }

        if(!$extensionDir)
        {
            return ['data'=>'暂不支持【.'.$fileExtension.'】格式的文件上传','status'=>'1002'];
        }

        if($file->size == 0)
        {
            $iniMaxFileSize = ini_get('upload_max_filesize');
            return ['data'=>'文件大小超过服务器设置限制'.$iniMaxFileSize,'status'=>'1003'];
        }

        $size=static::b2M($file->size);

        if($size > static::MAX_SIZE)
        {
            return ['data'=>'文件大小不能超过'.static::MAX_SIZE.'M','status'=>'1004'];
        }

        //如果用户自定义回调函数
        if(isset($createDirAndGetUploadInfoCallBack))
        {
            $uploadInfo = call_user_func($createDirAndGetUploadInfoCallBack);
        }
        else
        {
            $uploadInfo = static::autoCreateDirAndGetUploadInfo($basePath,$extensionDir,$urlPrefix,$fileExtension);
        }

        //保存文件
        if($file->saveAs($uploadInfo['path'].DIRECTORY_SEPARATOR.$uploadInfo['fileName']))
        {
            return ['data'=>$uploadInfo['url'].'/'.$uploadInfo['fileName'],'status'=>'200'];
        }

        return ['data'=>'上传失败','status'=>'1006'];
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
     * @param UploadedFile  $file         上传文件对象
     * @param string        $basePath     上传基础路径
     * @param string        $urlPrefix    url前缀，默认为空串
     * @param callable|null $createDirAndGetUploadInfoCallBack  创建上传目录和文件名并获取信息回调函数
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upImg(UploadedFile $file, $basePath, $urlPrefix='', callable $createDirAndGetUploadInfoCallBack=null)
    {
        return static::up($file,$basePath,$urlPrefix,static::IMG,$createDirAndGetUploadInfoCallBack);
    }

    /**传视频
     * @param UploadedFile  $file       上传文件对象
     * @param string        $basePath   上传基础路径
     * @param string        $urlPrefix  url前缀，默认为空串
     * @param callable|null $createDirAndGetUploadInfoCallBack  创建上传目录和文件名并获取信息回调函数
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upVideo(UploadedFile $file, $basePath, $urlPrefix='', callable $createDirAndGetUploadInfoCallBack=null)
    {
        return static::up($file,$basePath,$urlPrefix,static::VIDEO,$createDirAndGetUploadInfoCallBack);
    }

    /**传音频
     * @param UploadedFile  $file        上传文件对象
     * @param string        $basePath    上传基础路径
     * @param string        $urlPrefix   url前缀，默认为空串
     * @param callable|null $createDirAndGetUploadInfoCallBack  创建上传目录和文件名并获取信息回调函数
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upVoice(UploadedFile $file, $basePath, $urlPrefix='', callable $createDirAndGetUploadInfoCallBack=null)
    {
        return static::up($file,$basePath,$urlPrefix,static::VOICE,$createDirAndGetUploadInfoCallBack);
    }

    /**传文件
     * @param UploadedFile  $file        上传文件对象
     * @param string        $basePath    上传基础路径
     * @param string        $urlPrefix   url前缀，默认为空串
     * @param callable|null $createDirAndGetUploadInfoCallBack  创建上传目录和文件名并获取信息回调函数
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function upFile(UploadedFile $file, $basePath, $urlPrefix='', callable $createDirAndGetUploadInfoCallBack=null)
    {
        return static::up($file,$basePath,$urlPrefix,static::FILE,$createDirAndGetUploadInfoCallBack);
    }
}