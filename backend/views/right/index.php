<?php
    use backend\components\Html;
    use yii\helpers\Url;
    $this->title = '权限节点';
    $this->params['breadcrumbs'][] = ['label' => '节点管理', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin:30px 20px;">
    <div class="col-sm-4">
        <a href="<?=Url::toRoute(['add'])?>" class="btn btn-success">添加节点</a>
    </div>
    <div class="col-sm-4">
        <input type="text" id="moduleName" placeholder="模块名称或备注" class="form-control"/>
    </div>
    <div class="col-sm-4" style="margin-top: -5px;">
        <a class="btn btn-success btn-search">搜索</a>
    </div>
</div>

<table class="table">
    <tr>
        <th style="vertical-align: middle;">备注</th>
        <th>名称</th>
        <th>类型</th>
        <th>排序</th>
        <th>是否启用</th>
        <th>是否显示</th>
        <th>操作</th>
    </tr>
    <?php foreach ($list as $module):?>

        <tr class="module">
            <td><?=Html::encode($module['description'])?></td>
            <td><?=Html::encode($module['name'])?></td>
            <td>模块</td>
            <td><?=Html::encode($module['range'])?></td>
            <td><?=Html::dealAButton(Url::toRoute(['on','id'=>$module['id']]),$module['isOn'])?></td>
            <td><?=Html::dealAButton(Url::toRoute(['show','id'=>$module['id']]),$module['isShow'],'显示','隐藏')?></td>
            <td><a href="<?=Url::toRoute(['edit','id'=>$module['id']])?>" class="btn btn-success">编辑</a><p class="btn btn-danger delete" nodeId="<?=$module['id']?>" info="模块-<?=$module['name']?>">删除</p></td>
        </tr>

        <?php if(isset($module['controllerList'])):?>

            <?php foreach ($module['controllerList'] as $controller):?>

                <tr class="controller">
                    <td><?=Html::encode($controller['description'])?></td>
                    <td><?=Html::encode($controller['name'])?></td>
                    <td>控制器</td>
                    <td><?=Html::encode($controller['range'])?></td>
                    <td><?=Html::dealAButton(Url::toRoute(['on','id'=>$controller['id']]),$controller['isOn'])?></td>
                    <td><?=Html::dealAButton(Url::toRoute(['show','id'=>$controller['id']]),$controller['isShow'],'显示','隐藏')?></td>
                    <td><a href="<?=Url::toRoute(['edit','id'=>$controller['id']])?>" class="btn btn-success">编辑</a><p class="btn btn-danger delete" nodeId="<?=$controller['id']?>" info="控制器-<?=$controller['name']?>">删除</p></td>
                </tr>

                <?php if(isset($controller['actionList'])):?>
                    <?php foreach ($controller['actionList'] as $action):?>
                        <tr class="action">
                            <td><?=Html::encode($action['description'])?></td>
                            <td><?=Html::encode($action['name'])?></td>
                            <td>操作</td>
                            <td><?=Html::encode($action['range'])?></td>
                            <td></td>
                            <!--<td><?/*=Html::dealAButton(Url::toRoute(['on','id'=>$action['id']]),$action['isOn'])*/?></td>-->
                            <td></td>
                            <td><a href="<?=Url::toRoute(['edit','id'=>$action['id']])?>" class="btn btn-success">编辑</a><p class="btn btn-danger delete" nodeId="<?=$action['id']?>" info="操作-<?=$action['name']?>">删除</p></td>
                        </tr>
                    <?php endforeach;?>

                <?php endif;?>

            <?php endforeach;?>

        <?php endif;?>

    <?php endforeach;?>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $('.delete').on('click',function () {
            var msg='您真的要删除：【'+$(this).attr('info')+'】 吗？';
            if(confirm(msg))
            {
                var id=$(this).attr('nodeId');
                $.comAjax({
                    url:'<?=Url::toRoute(['del'])?>',
                    data:{id:id},
                    success:function (ret) {
                        if(ret.status && ret.status == '200'){
                            alert('删除成功');
                            window.location.reload();
                        }
                    }
                });
            }
        });

        $('.btn-search').on('click',function () {
            window.location='<?=Url::toRoute(['index'])?>?moduleName='+$('#moduleName').val();
        });

        $('#moduleName').on('keyup',function (e) {
            if(e.keyCode == 13){
                $('.btn-search').click();
            }
        });

        $('#moduleName').val('<?=$moduleName?>');

        if($('#moduleName').val().length>0)
        {
            $('#moduleName').focus();
        }
    });
</script>
