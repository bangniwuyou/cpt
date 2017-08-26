<?php
/**
 * 权限节点服务
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/29
 * Time: 17:56
 */

namespace backend\service;


use backend\components\StateHelper;
use backend\models\entity\RightEntity;
use common\utils\ComHelper;

class RightService extends BaseService
{
    /**权限实体
     * @var RightEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $rightEntity;

    /**
     * RightService constructor.
     */
    public function __construct()
    {
        $this->rightEntity=new RightEntity();
        $this->entity=$this->rightEntity;
    }

    /**得到所有可用的列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAllEnableList()
    {
        return $this->rightEntity->getAllEnableList();
    }

    /**获取所有权限列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAll()
    {
        return $this->rightEntity->getAllList();
    }

    /**删除节点
     * @param int   $id    节点id
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delete($id)
    {
        $this->rightEntity->del($id);
        MenuService::self()->delAllAdminMenu();
    }

    /**更新
     * @param array  $info      节点信息
     * @author 姜海强 <jhq0113@163.com>
     */
    public function update($info)
    {
        if($this->rightEntity->checkEnableUpdate($info['nodeName'],$info['level'],$info['parentId'],$info['id']))
        {
            $this->rightEntity->update($info);
            return true;
        }
        ComHelper::retState(StateHelper::$ERROR_NODE_EXISTS);
    }

    /**添加节点列表
     * @param array   $addArray    节点列表
     * @author 姜海强 <jhq0113@163.com>
     */
    public function addList($addArray)
    {
        if(is_array($addArray) && !empty($addArray))
        {
            foreach ($addArray as $item)
            {
                $item['nodeName']=trim($item['nodeName']);
                $item['desc']=trim($item['desc']);
                if($this->rightEntity->checkExist($item['nodeName'],$item['level'],$item['parentId']))
                {
                    continue;
                }
                $this->rightEntity->addByArray($item);
            }
            MenuService::self()->delAllAdminMenu();
        }
    }

    /**设置是否显示
     * @param int $id        权限节点id
     * @param int $status    状态码
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function show($id,$status)
    {
        return $this->rightEntity->show($id,$status);
    }
}