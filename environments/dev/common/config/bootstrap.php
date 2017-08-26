<?php

$rootPath = dirname(dirname(__DIR__));

//公共企业库
Yii::setAlias('@common', $rootPath.'/common');

//网站
Yii::setAlias('@www', $rootPath . '/www');

//网站后台
Yii::setAlias('@backend', $rootPath . '/backend');

//文件服务器，注意：必须与后台在同一台服务器,如果访问量大，可以增加CDN
Yii::setAlias('@file', $rootPath . '/file');

//定时任务
Yii::setAlias('@console', $rootPath . '/console');

//接口
Yii::setAlias('@rest', $rootPath . '/rest');


/**
 * 文件服务器根目录
 */
define('UPLOAD_SERVER_WEB_PATH',\Yii::getAlias('@file').'/web');

/**
 * 主域名
 */
define('DOMAIN','.83cloud.cn');

/**
 * 网站前台域名
 */
define('WEB_URL','http://www'.DOMAIN);

/**
 * 网站后台域名
 */
define('BACKEND_URL','http://www-backend'.DOMAIN);

/**
 * 接口域名
 */
define('REST_URL','http://rest'.DOMAIN);

/**
 * 文件域名
 */
define('FILE_URL','http://file'.DOMAIN);

/**
 * 分页大小
 */
define('PAGE_SIZE',20);

//跨域脚本
define('CROSSDOMAINSCRIPT','<script type="text/javascript">document.domain="'.DOMAIN.'";</script>');
