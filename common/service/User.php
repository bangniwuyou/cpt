<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 16:20
 */

namespace common\service;

/**
 * Class User
 * @package common\service
 * @author 姜海强 <jhq0113@163.com>
 */
class User extends Service
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->entity = \common\models\entity\User::self();

        $this->redis  = \common\models\redis\User::self();
    }
}