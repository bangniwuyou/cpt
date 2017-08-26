<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 18:08
 */

namespace common\utils;


use common\base\IUtils;

class StateHelper extends IUtils
{
    public static $SUCCESS=[
        'desc'=>'操作成功',
        'code' =>  '200'
    ];

    public static $ERROR_FOIBIDDEN=[
        'desc'=>'非法访问',
        'code'=>'1000'
    ];

    public static $ERROR_INNER=[
        'desc'=>'服务器内部错误',
        'code' =>  '500'
    ];

    public static $FAILURE=[
        'desc'=>'操作失败',
        'code' =>  '-1'
    ];

    public static $ERROR_LOGIN=[
        'desc'=>'用户名或密码错误',
        'code' =>   '400'
    ];

    public static $ERROR_LOGIN_FORBIDDEN=[
        'desc'=>'您被禁止登陆,请联系管理员',
        'code' =>   '401'
    ];

    public static $ERROR_VERIFY=[
        'desc'=>'验证码错误',
        'code' =>   '402'
    ];

    public static $ERROR_RIGHT=[
        'desc'=>'没有权限',
        'code' =>  '403'
    ];

    public static $ERROR_PARAM=[
        'desc'=>'参数错误',
        'code' =>  '404'
    ];

    public static $ERROR_PHONE=[
        'desc'=>'手机号格式错误',
        'code'=>'405'
    ];

    public static $ERROR_EMAIL=[
        'desc'=>'邮箱格式错误',
        'code'=>'407'
    ];

    public static $ERROR_IDCARD=[
        'desc'=>'身份证号码格式错误',
        'code'=>'408'
    ];

    public static $ERROR_USER=[
        'desc'=>'用户名必填',
        'code'=>'409'
    ];

    public static $ERROR_PHONE_EXIST=[
        'desc'=>'手机号已经被注册',
        'code'=>'410'
    ];

    public static $ERROR_IDCARD_EXIST=[
        'desc'=>'身份证号已被注册',
        'code'=>'411'
    ];

    public static $ERROR_PWD_LENGTH=[
        'desc'=>'密码长度必须为[6,25]',
        'code'=>'412'
    ];

    public static $ERROR_PHONE_NOT_EXIST=[
        'desc'=>'该手机号还没有注册',
        'code'=>'413'
    ];

    public static $ERROR_PASSWORD_LENGTH=[
        'desc'=>'登陆密码长度必须为[6,33]',
        'code'=>'414'
    ];

    public static $ERROR_PASSWORD=[
        'desc'=>'登陆密码错误',
        'code'=>'415'
    ];

    public static $ERROR_LINK_EXPIRE=[
        'desc'=>'链接已失效，请刷新页面重试',
        'code'=>'416'
    ];

    public static $ERROR_USER_NAME_EXIST=[
        'desc'=>'用户名已存在',
        'code'=>'417'
    ];

    public static $ERROR_USER_NAME_DIRTY=[
        'desc'=>'用户名含有非法字符串',
        'code'=>'418'
    ];

    public static $ERROR_NOT_LOGIN=[
        'desc'=>'请先登录再来操作',
        'code'=>'419'
    ];

    public static $ERROR_EMAIL_EXIST=[
        'desc'=>'该邮箱已经被注册',
        'code'=>'420'
    ];

    public static $ERROR_TABLE=[
        'desc'=>'表已经存在',
        'code'=>'606'
    ];

    public static $ERROR_TABLE_NOT_EXIST=[
        'desc'=>'表不存在',
        'code'=>'607'
    ];

    public static $ERROR_COLUM=[
        'desc'=>'字段已经存在',
        'code'=>'608'
    ];

    public static $ERROR_COLUM_NOT_EXIST=[
        'desc'=>'字段不存在',
        'code'=>'609'
    ];


    public static $ERROR_SEND_MORE=[
        'desc'=>'一分钟只能发送一次',
        'code'=>'610'
    ];

    //----------------菜单节点-------------
    public static $ERROR_NODE_LEVEL=[
        'desc'=>'节点等级必须为数字',
        'code'=>'700'
    ];

    //------------------上传------------
    public static $ERROR_UPLOAD_TOO_BIG=[
        'desc'=>'文件大小超出了服务器限制',
        'code'=>'800'
    ];

    public static $ERROR_UPLOAD_EXTENSION=[
        'desc'=>'图片扩展名只能为:(gif,jpg,jpeg,png,bmp)',
        'code'=>'801'
    ];

    public static $ERROR_UPLOAD_NO_SELECT=[
        'desc'=>'请选择图片',
        'code'=>'802'
    ];
}