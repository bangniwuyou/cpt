<?php
/**
 * 权限节点
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 19:25
 */

namespace backend\models\entity;


/**菜单节点
 * Class RightEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class RightEntity extends BaseEntity
{
    public $tableName='{{%admin_rights}}';

    /**
     * 模块
     */
    const MODULE=1;

    /**
     * 控制器
     */
    const CONTROLLER=2;

    /**
     * 操作
     */
    const ACTION=3;

    public $fields=[
        'id',
        'name',
        'description',
        'level',
        'is_on',
        'is_show',
        'range',
        'parent_id'
    ];

    /**得到所有权限列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAllList()
    {
        return $this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' ORDER BY `level` ASC,`range` DESC',
            [],
            true
        );
    }

    /**得到所有可用的权限列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAllEnableList()
    {
        return $this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `is_on`=1 ORDER BY `level` ASC,`range` DESC',
            [],
            true
        );
    }

    /**根据权限id列表获取权限
     * @param array   $ids    权限id列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getListByIds($ids)
    {
        if(is_array($ids) && !empty($ids))
        {
            foreach ($ids as &$value)
            {
                $value=(int)$value;
            }
            return $this->query(
                'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `id` IN ('.implode(',',$ids).') ORDER BY `level` ASC,`range` DESC',
                [],
                true
            );
        }
        return [];
    }

    /**根据权限id列表获取可用权限
     * @param array   $ids  权限id列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getEnableListByIds($ids)
    {
        if(is_array($ids) && !empty($ids))
        {
            foreach ($ids as &$value)
            {
                $value=(int)$value;
            }

            return $this->query(
                'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `is_on`=1 AND `id` IN ('.implode(',',$ids).') ORDER BY `level` ASC,`range` DESC ',
                [],
                true
            );
        }
        return [];
    }

    /**检查节点是否已经存在
     * @param string  $name      节点名称
     * @param int     $level     节点等级
     * @param int     $parent_id 父节点ID
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkExist($name,$level,$parentId)
    {
        $rightId=$this->query(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `name`=:name AND `level`=:level AND `parent_id`=:parent_id LIMIT 1',
            [
                ':name'=>$name,
                ':level'=>$level,
                ':parent_id'=>$parentId
            ],
            true
        );
        if(!empty($rightId))
        {
            return true;
        }
        return false;
    }

    /**校验是否可以更新
     * @param string   $name        节点名称
     * @param int      $level       节点类型
     * @param int      $parentId    父节点id
     * @param int      $id          节点id
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkEnableUpdate($name,$level,$parentId,$id)
    {
        $rightId=$this->query(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `name`=:name AND `level`=:level AND `parent_id`=:parent_id LIMIT 1',
            [
                ':name'=>$name,
                ':level'=>$level,
                ':parent_id'=>$parentId
            ],
            true
        );
        if(!empty($rightId))
        {
            return $rightId[0]['id'] == $id;
        }
        return true;
    }

    /**添加节点
     * @param array   $item    节点元素数组
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function addByArray($item)
    {
        return $this->execute(
            'INSERT INTO '.$this->getTableName().'(`name`,`description`,`level`,`is_on`,`range`,`parent_id`,`is_show`)VALUES(:name,:description,:level,:is_on,:range,:parent_id,:is_show)',
            [
                ':name'=>$item['nodeName'],
                ':description'=>$item['desc'],
                ':level'=>(int)$item['level'],
                ':is_on'=>(int)$item['isOn'],
                ':range'=>(int)$item['sort'],
                ':parent_id'=>(int)$item['parentId'],
                ':is_show'=>(int)$item['isShow']
            ]
        );
    }

    /**更新
     * @param array   $info    节点信息
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function update($info)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET `parent_id`=:parent_id,`is_on`=:is_on,`is_show`=:is_show,`name`=:name,`description`=:description,`level`=:level,`range`=:range WHERE `id`=:id LIMIT 1',
            [
                ':id'=>$info['id'],
                ':parent_id'=>$info['parentId'],
                ':name'=>$info['nodeName'],
                ':is_on'=>$info['isOn'],
                ':is_show'=>$info['isShow'],
                ':level'=>$info['level'],
                ':description'=>$info['desc'],
                ':range'=>$info['sort']
            ]
        );
    }

    /**设置是否显示
     * @param int $id        权限节点id
     * @param int $status    状态码
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function show($id,$status)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET `is_show`=:is_show WHERE `id`=:id LIMIT 1',
            [
                ':id'=>$id,
                ':is_show'=>$status
            ]
        );
    }
}