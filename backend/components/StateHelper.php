<?php
/**
 * Created by PhpStorm.
 * User: 姜海强
 * Date: 2016/10/14
 * Time: 15:49
 */

namespace backend\components;

/**
 * Class StateHelper
 * @package backend\components
 * @author 姜海强 <jhq0113@163.com>
 */
class StateHelper extends \common\utils\StateHelper
{
	public static $ERROR_LOGIN_EXPIRE=[
		'desc'=>'登录信息已经失效',
		'code'=>'414'
	];

	public static $ERROR_NODE_EXISTS=[
	    'desc'=>'节点已经存在',
        'code'=>'501'
    ];

	public static $ERROR_ROLE_EXISTS=[
	    'desc'=>'角色已经存在',
        'code'=>'502'
    ];

	public static $ERROR_ADMIN_EXISTS=[
	    'desc'=>'管理员已经存在',
        'code'=>'503'
    ];
}