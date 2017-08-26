<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/2
 * Time: 17:25
 */

namespace common\base;
use common\utils\ComHelper;

/**
 * Class IDb
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
abstract class IDb extends IService
{
    /**主键
     * @var string
     * @author 姜海强 <jhq0113@163.com>
     */
    public $primaryKey='id';

    /**表名
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $tableName;

    /**常用字段
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $fields=[

    ];

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    private $_specialMark=[
        '`',
        '{'
    ];

    /**获取表名
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getTableName()
    {
        return $this->formatMysqlKey($this->tableName);
    }

    /**格式化字段或表名称
     * @param string    $key   字段或表名
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function formatMysqlKey($key)
    {
        $needle=substr($key,0,1);
        if(in_array($needle,$this->_specialMark))
        {
            return $key;
        }
        return '`'.$key.'`';
    }

    /**格式化字段，下划线分割转小驼峰
     * @param array $fields         字段数组
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    protected function formatFields(array $fields=[])
    {
        if(!empty($fields))
        {
            foreach ($fields as $key=>$field)
            {
                $tuoFengField=ComHelper::line2TuoFeng($field);
                $field='`'.$field.'`';
                if($field != $tuoFengField)
                {
                    $field=$field.' AS `'.$tuoFengField.'`';
                }
                $fields[$key]=$field;
                unset($tuoFengField);
            }
            return ' '.implode(',',$fields).' ';
        }
        return ' * ';
    }

    /**获取数据库连接
     * @return \yii\db\Connection
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getDb()
    {
        return \Yii::$app->db;
    }

    /**从库查询
     * @param string $sql                SQL语句
     * @param array  $params             参数
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    private function _slaveQuery($sql,$params=[])
    {
        return $this->getDb()->createCommand($sql,$params)->queryAll();
    }

    /**使用主库查询
     * @param string $sql      SQL语句
     * @param array  $params   参数
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    private function _masterQuery($sql,$params=[])
    {
        return $this->getDb()->useMaster(function ($db) use($sql,$params){
            return $db->createCommand($sql,$params)->queryAll();
        });
    }

    /**查询
     * @param string  $sql           SQL语句
     * @param array   $params        参数
     * @param bool    $useMaster     是否使用主库
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function query($sql,$params=[],$useMaster=false)
    {
        return $useMaster?$this->_masterQuery($sql,$params,$useMaster):$this->_slaveQuery($sql,$params,$useMaster);
    }

    /**查询单条记录
     * @param string $sql           SQL语句
     * @param array  $params        参数
     * @param bool   $userMaster    是否使用主库
     * @return array|mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public function queryOne($sql,$params=[],$userMaster=false)
    {
        $result=$this->query($sql.' LIMIT 1',$params,$userMaster);
        if(!empty($result))
        {
            return $result[0];
        }
        return $result;
    }

    /**写操作
     * @param string $sql             SQL语句
     * @param array  $params          参数
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function execute($sql,$params=[])
    {
        return $this->getDb()->createCommand($sql,$params)->execute();
    }


    /**向表中插入数据，插入成功返回插入ID，失败返回false
     * @param array  $colums   键值对，字段为键
     * @return bool|string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function insert(array $colums)
    {
        $fields=[];
        $params=[];
        foreach ($colums as $k=>$v)
        {
            array_push($fields,'`'.$k.'`');
            $params[':'.$k]=$v;
        }
        $sql='INSERT INTO '.$this->getTableName().'('.implode(',',$fields).')VALUES('.implode(',',array_keys($params)).')';
        $conn=$this->getDb();
        $res=$conn->createCommand($sql,$params)->execute();
        unset($fields,$params);
        if($res)
        {
            return $conn->lastInsertID;
        }
        return false;
    }

    /**更新操作
     * @param int    $id       主键值
     * @param array  $colums   键值对，字段为键
     * @author 姜海强 <jhq0113@163.com>
     */
    public function updateByIdForColums($id,array $colums)
    {
        if($id >0 && !empty($colums))
        {
            $sets=[];
            $params=[
                ':id'=>$id
            ];
            foreach ($colums as $field=>$value)
            {
                $param=':'.$field;
                array_push($sets,$this->formatMysqlKey($field).'='.$param);
                $params[ $param ] =$value;
            }
            return $this->execute('UPDATE '.$this->getTableName().' SET '.implode(',',$sets).' WHERE '.$this->primaryKey.'=:id LIMIT 1',$params);
        }


    }

    /**删除信息
     * @param int $id    主键值
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delete($id)
    {
        if( $id > 0)
        {
            return $this->execute('DELETE FROM '.$this->getTableName().' WHERE '.$this->formatMysqlKey($this->primaryKey).'=:id LIMIT 1',[
                ':id'=>$id
            ]);
        }
    }

    /**检查表是否存在
     * @param string    $tableName   表名称
     * @param bool      $useMaster   是否使用主库，默认使用主库
     * @return bool     存在返回true,不存在返回false
     * @author 姜海强 <jhq0113@163.com>
     */
    public function tableExists($tableName,$useMaster=true)
    {
        $result=$this->query('SHOW TABLES LIKE \''.$tableName.'\'',[],$useMaster);
        return !empty($result);
    }

    /**获取信息
     * @param int|string $value     值
     * @param string     $field     字段，默认为主键
     * @param bool       $useMaster 是否使用主库，默认为false
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getInfo($value,$field=null,$useMaster=false)
    {
        $field=isset($field) ? $field : $this->primaryKey;

        return $this->queryOne(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE '.$this->formatMysqlKey($field).'=:value',
            [
                ':value'=>$value
            ],
            $useMaster
        );
    }

    /**根据ID列表获取数据列表
     * @param array     $idList    id列表
     * @param string    $field     字段名称，默认为id
     * @param bool      $useMaster 是否使用主库
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getList($idList,$field='id',$useMaster=false)
    {
        $data=[];
        if(!empty($idList))
        {
            $idList = array_map(function($value){
                return (int) $value;
            },$idList);
            return $this->query('SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `'.$field.'` IN ('.implode(',',$idList).')',
                [],
                $useMaster);
        }
        return $data;
    }

    /**普通分页形式获取列表数据
     * @param string  $addition    WHERE、ORDER BY等限定
     * @param array   $params      参数
     * @param bool    $useMaster     是否使用主库
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getListWithCommonPagination($addition='',$params=[],$useMaster=false)
    {
        $pageSize = ComHelper::fIntG('pageSize');
        $page     = ComHelper::fIntG('page');
        $pageSize = ($pageSize >0) ? $pageSize : PAGE_SIZE;

        $page=($page < 2) ? 1 : $page;

        $data=[
            'pageSize'=>$pageSize,
            'page'=>$page,
            'totalPage'=>0,
            'totalCount'=>0,
            'list'=>[]
        ];

        $count=$this->query('SELECT COUNT(*) AS `num` FROM '.$this->getTableName().' '.$addition,$params,$useMaster);
        if(!empty($count) && $count[0]['num']>0)
        {
            $data['totalCount']=$count[0]['num'];

            $data['totalPage']=ceil($data['totalCount']/$pageSize);

            $offset=($page-1)*$pageSize;

            $data['list']=$this->query('SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' '.$addition.' LIMIT '.(string)$offset.','.$pageSize,$params,$useMaster);
        }

        return $data;
    }

    /**根据id分页形式获取列表数据
     * @param string $where      条件语句
     * @param int    $sort       排序，SORT_DESC,SORT_ASC
     * @param array  $params     参数
     * @param bool   $useMaster  是否使用主库
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getListWithIdPagination($where='',$sort=SORT_DESC,$params=[],$useMaster=false)
    {
        $start = ComHelper::fIntG('start');
        $limit = ComHelper::fIntG('pageSize');

        $data=[
            'start'         =>  $start,
            'pageSize'      =>  $limit,
            'list'          =>  [],
            'nextStart'     =>  0   //0表示没有数据了
        ];

        switch ($sort)
        {
            case SORT_DESC:
                $idSelect = ' ,MIN(`'.$this->primaryKey.'`) AS `selectId` ';
                $idWhere = ($start >0) ? ' `'.$this->primaryKey.'` < '.$start : '';
                $sortString = ' DESC ';
                break;
            case SORT_ASC:
                $idSelect = ' ,MAX(`'.$this->primaryKey.'`) AS `selectId`';
                $idWhere = ($start >0) ? ' `'.$this->primaryKey.'` > '.$start : '';
                $sortString = ' ASC ';
                break;
            default:
                return $data;
        }

        //表名称
        $tableName = $this->getTableName();

        $count=$this->query('SELECT COUNT(*) AS `count`'.$idSelect.' FROM `'.$tableName.'` '.$where.' ',[],$useMaster);

        $data['totalCount'] =   (int)$count[0]['count'];

        //如果有数据
        if($data['totalCount'] >0)
        {
            $where = empty($where) ? ' WHERE '.$idWhere.' ' : $where.' AND ('.$idWhere.') ';
            //查分页数据
            $data['list']=$this->query('SELECT '.$this->formatFields($this->fields).' FROM `'.$tableName.'` '.$where.' ORDER BY `'.$this->primaryKey.'` '.$sortString.' LIMIT '.(int)$limit,
                $params,
                $useMaster);

            if(!empty($data['list']))
            {
                $selectIndex=count($data['list']) - 1;
                //当前选择ID不等于列表最后id表示还有数据
                if($data['list'][ $selectIndex ]['id'] !== $count[0]['selectId'])
                {
                    $data['nextStart'] = $data['list'][ $selectIndex ]['id'];
                }
            }
        }
        return $data;
    }
}