<?php
use backend\components\Html;
use \yii\helpers\Url;

$this->title = '添加权限';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .info{
        margin: 5px;
    }
    .red{
        color: red;
    }

    .green{
        color: #5cb85c;
    }

</style>
<h3>
    角色：【<?=Html::encode($roleName)?>】权限分配
</h3>
<table class="table">
    <tr>
        <th>备注</th>
        <th>名称</th>
        <th>类型</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($list as $module):?>

        <tr class="module">
            <td><?=Html::encode($module['description'])?></td>
            <td><?=Html::encode($module['name'])?></td>
            <td>模块</td>
            <td class="msg">
                <span class="info"></span>
            </td>
            <td>
                <p class="btn btn-node" id="node-<?=$module['id']?>" level="1" nodeId="<?=$module['id']?>"></p>
                <p class="btn btn-danger btn-submit">提交</p>
            </td>
        </tr>

        <?php if(isset($module['controllerList'])):?>

            <?php foreach ($module['controllerList'] as $controller):?>

                <tr class="controller">
                    <td><?=Html::encode($controller['description'])?></td>
                    <td><?=Html::encode($controller['name'])?></td>
                    <td>控制器</td>
                    <td class="msg">
                        <span class="info"></span>
                    </td>
                    <td>
                        <p class="btn btn-node" id="node-<?=$controller['id']?>" level="2" moduleId="<?=$module['id']?>" nodeId="<?=$controller['id']?>"></p>
                        <p class="btn btn-danger btn-submit">提交</p>
                    </td>
                </tr>

                <?php if(isset($controller['actionList'])):?>
                    <?php foreach ($controller['actionList'] as $action):?>
                        <tr class="action">
                            <td><?=Html::encode($action['description'])?></td>
                            <td><?=Html::encode($action['name'])?></td>
                            <td>操作</td>
                            <td class="msg">
                                <span class="info"></span>
                            </td>
                            <td>
                                <p class="btn btn-node" id="node-<?=$action['id']?>" level="3" moduleId="<?=$module['id']?>" controllerId="<?=$controller['id']?>" nodeId="<?=$action['id']?>"></p>
                                <p class="btn btn-danger btn-submit">提交</p>
                            </td>
                        </tr>
                    <?php endforeach;?>

                <?php endif;?>

            <?php endforeach;?>

        <?php endif;?>

    <?php endforeach;?>
</table>
<script type="text/javascript">
    var MODULE='1';
    var CONTROLLER='2';
    var ACTION='3';
    var roleId='<?=$roleId?>';
    var ownList=<?=json_encode($ownList)?>;

    function add(item) {
        item.addClass('btn-warning').removeClass('btn-success').html('收回');
        var info=item.parent().siblings('.msg').find('.info');
        info.html('已添加');
        info.addClass('green').removeClass('red');
    }

    function remove(item) {
        item.addClass('btn-success').removeClass('btn-warning').html('添加');
        var info=item.parent().siblings('.msg').find('.info');
        info.html('已收回');
        info.addClass('red').removeClass('green');
    }

    function addToOwnList(id) {
        var index=ownList.indexOf(id);
        if(index > -1){
            return;
        }
        ownList.push(id);
    }

    function delFromOwnList(id) {
        var index=ownList.indexOf(id);
        if(index > -1){
            ownList.splice(index,1);
        }
    }

    $(document).ready(function () {
        $('.btn-node').each(function (index) {
            var nodeId=$(this).attr('nodeId');
            if(ownList.indexOf(nodeId) > -1){
                add($(this));
            }
            else{
                remove($(this));
            }
        });

        $('.btn-node').on('click',function () {
            var level=$(this).attr('level');
            var nodeId=$(this).attr('nodeId');
            var moduleId='';
            var controllerId='';
            switch (level)
            {
                case MODULE:
                    break;
                case CONTROLLER:
                    moduleId=$(this).attr('moduleId');
                    break;
                case ACTION:
                    moduleId=$(this).attr('moduleId');
                    controllerId=$(this).attr('controllerId');
                    break;
            }

            if($(this).hasClass('btn-success'))
            {
                add($(this));
                ownList.push(nodeId);
                if(moduleId.length>0){
                    add($('#node-'+moduleId));
                    addToOwnList(moduleId);
                }
                if(controllerId.length>0){
                    add($('#node-'+controllerId))
                    addToOwnList(controllerId);
                }
            }
            else{
                remove($(this));
                delFromOwnList(nodeId);
            }
        });

        $('.btn-submit').on('click',function () {
            if(confirm('您真的要提交所有的修改吗？')){
                if(ownList.length < 1 ){
                    if(!confirm('您真的要移除该角色的所有权限吗？')){
                        return;
                    }
                }
                $.comAjax({
                    url:'<?=Url::toRoute(['add-right'])?>',
                    data:{rightList:ownList,roleId:roleId},
                    success:function (ret) {
                        if(ret.status && ret.status == '200'){
                            alert('修改成功');
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });
</script>
