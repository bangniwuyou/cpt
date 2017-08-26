<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use backend\models\entity\RightEntity;
    $this->title = '编辑权限节点';
    $this->params['breadcrumbs'][] = ['label' => '节点管理', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h3>节点属性</h3>
    <div class="form-group">
        <label class="control-label" for="level">节点类型</label>
        <select id="level" class="form-control select-edit" attrId="<?=$info['level']?>">
            <option value="<?=RightEntity::MODULE?>">模块-module</option>
            <option value="<?=RightEntity::CONTROLLER?>">控制器-controller</option>
            <option value="<?=RightEntity::ACTION?>">操作-action</option>
        </select>

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="node_name">节点名称(限英文)</label>
        <input type="text" id="node_name" value="<?=$info['name']?>" class="form-control" maxlength="64">

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="description">节点备注</label>
        <input type="text" id="description" value="<?=$info['description']?>" class="form-control" maxlength="32">

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="parent_id">父节点,模块父节点不用选</label>
        <select id="parent_id" class="form-control" attrId="<?=$info['parentId']?>">
            <option value="0">无</option>
        </select>

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="range">排序</label>
        <input type="number" id="range" value="<?=$info['range']?>" class="form-control" value="0" maxlength="3">

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="is_show">是否显示</label>
        <select id="is_show" class="form-control select-edit" attrId="<?=$info['isShow']?>">
            <option value="1">是</option>
            <option value="0">否</option>
        </select>

        <div class="help-block war"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="is_on">是否启用</label>
        <select id="is_on" class="form-control select-edit" attrId="<?=$info['isOn']?>">
            <option value="1">是</option>
            <option value="0">否</option>
        </select>

        <div class="help-block war"></div>
    </div>

    <div class="form-group">
        <p class="btn btn-success form-control" id="submit">提交</p>
    </div>
</div>
<script type="text/javascript">
    var rightList=<?=json_encode($rightList)?>;

    var MODULE='<?=RightEntity::MODULE?>';
    var CONTROLLER='<?=RightEntity::CONTROLLER?>';
    var ACTION='<?=RightEntity::ACTION?>';

    var level=$('#level');
    var node_name=$('#node_name');
    var description=$('#description');
    var parent_id=$('#parent_id');
    var range=$('#range');
    var is_on=$('#is_on');
    var is_show=$('#is_show');

    function fillParents(nodeType) {
        var level=0;
        if(nodeType == MODULE){
            parent_id.html('<option value="0">无</option>');
            return;
        }
        else if(nodeType == CONTROLLER){
            level=MODULE;
        }
        else if(nodeType == ACTION) {
            level=CONTROLLER;
        }

        parent_id.empty();

        if(level != 0){
            for(var k in rightList){
                if(rightList[k]['level'] == level){
                    parent_id.append('<option value="'+rightList[k]['id']+'">'+getModuleNameById(rightList[k]['parentId'])+rightList[k]['description']+'</option>');
                }
            }
        }
    }

    function getModuleNameById(id) {
        for(var k in rightList){
            if(rightList[k]['level'] == MODULE && id == rightList[k]['id']){
                return rightList[k]['name']+'--';
            }
        }
        return '';
    }

    $(document).ready(function () {
        /**
         * 修改节点类型
         */
        level.on('change',function () {
            var nodeType=$(this).val();
            fillParents(nodeType);
        });


        $('.select-edit').each(function (index) {
            var val=$(this).attr('attrId');
            $(this).val(val);
        });

        fillParents(<?=(int)$info['level']?>);
        parent_id.val(parent_id.attr('attrId'));

        $('#submit').on('click',function () {
            var node={id:<?=$info['id']?>};
            node.nodeName=checkRequire(node_name,'节点名称');
            if(node.nodeName){
                node.desc=checkRequire(description,'节点备注');
                if(node.desc){
                    node.level=level.val();
                    node.parentId=parent_id.val();
                    node.parentName=$('#parent_id option:selected').text();
                    node.sort=range.val();
                    node.isOn=is_on.val();
                    node.isShow=is_show.val();
                    $.comAjax({
                        url:'edit',
                        data:node,
                        success:function (ret) {
                            if(ret.status && ret.status == '200'){
                                alert('修改成功');
                                window.location.reload();
                            }
                            else if(ret.data){
                                is_on.siblings('.war').html('节点已经存在');
                            }
                        }
                    });
                }
            }
        });
    });
</script>