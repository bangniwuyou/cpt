<?php
    use backend\components\Html;
    use yii\helpers\Url;

    $this->title = '角色列表';
    $this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div style="margin:30px 20px;">
        <a href="<?=Url::toRoute(['add'])?>" class="btn btn-success">添加角色</a>
</div>

<table class="table">
    <tr>
        <th>名称</th>
        <th>是否启用</th>
        <th>添加时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($list as $role):?>
        <tr>
            <td><?=Html::encode($role['roleName'])?></td>
            <td><?=Html::dealAButton(Url::toRoute(['on','id'=>$role['id']]),$role['isOn'])?></td>
            <td><?=Html::encode($role['addTime'])?></td>
            <td><?=Html::encode($role['updateTime'])?></td>
            <td>
                <a href="<?=Url::toRoute(['edit','id'=>$role['id']])?>" class="btn btn-success">编辑</a>
                <a href="<?=Url::toRoute(['add-right','id'=>$role['id'],'roleName'=>$role['roleName']])?>" class="btn btn-warning">编辑权限</a>
                <a class="btn btn-danger btn-del" delUrl="<?=Url::toRoute(['del','id'=>$role['id']])?>" info="您真的要删除角色：【<?=$role['roleName']?>】吗？">删除</a>
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