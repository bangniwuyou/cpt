<?php
/**
 * 上传控制器
 * User: 姜海强
 * Date: 2016/10/17
 * Time: 14:05
 */

namespace backend\controllers;

use bootstrap_fileinput\DeleteAction;
use common\utils\ComHelper;
use common\utils\UploadHelper;
use summer\UploadAction;

class UpController extends BackendController
{
	//禁止CSRF验证
	public $enableCsrfValidation=false;

    /**编辑器上传
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'common\widgets\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => FILE_URL,
                    "imagePathFormat" => "/editor/uploads/images/{yyyy}/{mm}/{dd}/{hh}_{ii}_{ss}_{rand:8}",
                    "imageRoot" => UPLOAD_SERVER_WEB_PATH
                ]
            ],
            'summer' =>[
                'class' => UploadAction::class,
            ],
            'input'  =>[
                'class'         =>  \common\widgets\upload\UploadAction::class,
                'uploadPath'    =>  \Yii::getAlias('@webroot/bootstrap-uploads'),
                'urlPrefix'     => 'http://'.$_SERVER['HTTP_HOST'].'/bootstrap-uploads'
            ],
        ];
    }

    /**传图片
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionImg()
    {
        $name=ComHelper::fStringP('name');
        $file=UploadHelper::getFileByName($name);
        if(!$file)
        {
            ComHelper::retArray([
                'status'=>'404',
                'data'=>'请选择要上传的图片文件'
            ]);
        }
        $upInfo=UploadHelper::upImg($file,UPLOAD_SERVER_WEB_PATH);
        $upInfo['data']=$upInfo['message'];
        unset($upInfo['message']);
        ComHelper::retArray($upInfo);
    }

    /**上传文件
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionFile()
    {
        $name=ComHelper::fStringP('name');
        $file=UploadHelper::getFileByName($name);
        if(!$file)
        {
            ComHelper::retArray([
                'status'=>'404',
                'data'=>'请选择要上传的文件'
            ]);
        }
        $upInfo=UploadHelper::upFile($file,UPLOAD_SERVER_WEB_PATH);
        $upInfo['data']=$upInfo['message'];
        unset($upInfo['message']);
        ComHelper::retArray($upInfo);
    }

    /**上传视频文件
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionVideo()
    {
        $name=ComHelper::fStringP('name');
        $file=UploadHelper::getFileByName($name);
        if(!$file)
        {
            ComHelper::retArray([
                'status'=>'404',
                'data'=>'请选择要上传的视频文件'
            ]);
        }
        $upInfo=UploadHelper::upVideo($file,UPLOAD_SERVER_WEB_PATH);
        $upInfo['data']=$upInfo['message'];
        unset($upInfo['message']);
        ComHelper::retArray($upInfo);
    }

    /**上传音频文件
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionVoice()
    {
        $name=ComHelper::fStringP('name');
        $file=UploadHelper::getFileByName($name);
        if(!$file)
        {
            ComHelper::retArray([
                'status'=>'404',
                'data'=>'请选择要上传的音频文件'
            ]);
        }
        $upInfo=UploadHelper::upVoice($file,UPLOAD_SERVER_WEB_PATH);
        $upInfo['data']=$upInfo['message'];
        unset($upInfo['message']);
        ComHelper::retArray($upInfo);
    }
}