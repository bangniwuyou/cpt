<?php
use backend\components\Html;
use yii\helpers\Url;

$this->title = '管理员列表';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="margin:30px 20px;">
    <a href="<?=Url::toRoute(['add'])?>" class="btn btn-success">添加管理员</a>
</div>

<table class="table">
    <tr>
        <th>管理员名称</th>
        <th>登录名</th>
        <th>是否为超级管理员</th>
        <th>是否启用</th>
        <th>最后登录IP地址</th>
        <th>添加时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($list as $admin):?>
        <tr>
            <td><?=Html::encode($admin['trueName'])?></td>
            <td><?=Html::encode($admin['userName'])?></td>
            <td><?=Html::dealAButton(Url::toRoute(['super','id'=>$admin['id']]),$admin['isSuperAdmin'],'任命','收回')?></td>
            <td><?=Html::dealAButton(Url::toRoute(['on','id'=>$admin['id']]),$admin['isOn'])?></td>
            <td><?=Html::encode(long2ip($admin['lastLoginIp']))?></td>
            <td><?=Html::encode($admin['addTime'])?></td>
            <td><?=Html::encode($admin['updateTime'])?></td>
            <td>
                <a href="<?=Url::toRoute(['edit','id'=>$admin['id']])?>" class="btn btn-success">编辑</a>
                <a href="<?=Url::toRoute(['password','id'=>$admin['id'],'userName'=>$admin['userName']])?>" class="btn btn-warning">重置登录密码</a>
                <a class="btn btn-warning" href="<?=Url::toRoute(['user-role','id'=>$admin['id'],'userName'=>$admin['userName']])?>">角色管理</a>
                <a class="btn btn-danger btn-del" delUrl="<?=Url::toRoute(['del','id'=>$admin['id']])?>" info="您真的要删除管理员：【<?=$admin['userName']?>】吗？">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-del').on('click',function () {
            var msg=$(this).attr('info');
            if(confirm(msg)){
                window.location=$(this).attr('delUrl');
            }
        });
    });
</script>