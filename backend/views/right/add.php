<?php
    use backend\models\entity\RightEntity;
    use \yii\helpers\Url;

    $this->title = '添加权限节点';
    $this->params['breadcrumbs'][] = ['label' => '节点管理', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="col-sm-6">
        <h3>节点属性</h3>
        <div class="form-group">
            <label class="control-label" for="level">节点类型</label>
            <select id="level" class="form-control">
                <option value="<?=RightEntity::MODULE?>">模块-module</option>
                <option value="<?=RightEntity::CONTROLLER?>">控制器-controller</option>
                <option value="<?=RightEntity::ACTION?>">操作-action</option>
            </select>

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="node_name">节点名称(限英文)</label>
            <input type="text" id="node_name" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="description">节点备注</label>
            <input type="text" id="description" class="form-control" maxlength="32">

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="parent_id">父节点,模块父节点不用选</label>
            <select id="parent_id" class="form-control">
                <option value="0">无</option>
            </select>

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="range">排序</label>
            <input type="number" id="range" class="form-control" value="0" maxlength="3">

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="is_show">是否显示</label>
            <select id="is_show" class="form-control">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>

            <div class="help-block war"></div>
        </div>
        <div class="form-group">
            <label class="control-label" for="is_on">是否启用</label>
            <select id="is_on" class="form-control">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <p class="btn btn-success form-control" id="adding_node"> &gt;&gt;</p>
        </div>
    </div>
    <div class="col-sm-6">
        <h3>待添加节点</h3>
        <table class="table">
            <tr>
                <th>节点类型</th>
                <th>节点名称</th>
                <th>节点描述</th>
                <th>父节点</th>
                <th>排序</th>
                <th>是否启用</th>
                <th>是否显示</th>
                <th>操作</th>
            </tr>
            <tbody id="adding">

            </tbody>
            <tr>
                <td colspan="7">
                    <p class="btn btn-success form-control" id="btn_add_all">全部添加</p>
                </td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">

    var rightList=<?=json_encode($rightList)?>;

    var MODULE='<?=RightEntity::MODULE?>';
    var CONTROLLER='<?=RightEntity::CONTROLLER?>';
    var ACTION='<?=RightEntity::ACTION?>';

    var nodeTypeList={
        '<?=RightEntity::MODULE?>':'模块',
        '<?=RightEntity::CONTROLLER?>':'控制器',
        '<?=RightEntity::ACTION?>':'操作'
    };

    var adding=$('#adding');

    var level=$('#level');
    var node_name=$('#node_name');
    var description=$('#description');
    var parent_id=$('#parent_id');
    var range=$('#range');
    var is_on=$('#is_on');
    var is_show=$('#is_show');
    var adding_node=$('#adding_node');
    var add_all=$('#btn_add_all');
    var addAllUrl='<?=Url::toRoute(['/right/add'])?>';

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

    /**
     * 待添加数据
     * @type {Array}
     */
    var adding_data=[];

    /**
     * 检查待添加节点元素是否已经添加
     * @param {object}  item   节点元素
     */
    function checkExists(item) {
        var len=adding_data.length;
        if(len>0){
            for(var k=0;k<len;k++){
                if(adding_data[k]['level'] == item.level && adding_data[k]['nodeName'] == item.nodeName){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 删除元素
     * @param {object}  item   节点元素
     */
    function delItem(item) {
        var len=adding_data.length;
        if(len>0){
            for(var k=0;k<len;k++){
                if(adding_data[k]['level'] == item.level && adding_data[k]['nodeName'] == item.nodeName){
                    adding_data.splice(k,1);
                }
            }
        }
    }

    /**
     * 向待添加列表添加元素
     * @param {object}  item   节点元素
     */
    function fillAddingData(item) {
        var tr='<tr>';
        tr+='<td>'+nodeTypeList[item.level]+'</td>';
        tr+='<td>'+item.nodeName+'</td>';
        tr+='<td>'+item.desc+'</td>';
        tr+='<td>'+item.parentName+'</td>';
        tr+='<td>'+item.sort+'</td>';
        tr+='<td>'+((item.isOn == '1') ? '是' : '否')+'</td>';
        tr+='<td>'+((item.isShow == '1') ? '是' : '否')+'</td>';
        tr+='<td level="'+item.level+'" nodeName="'+item.nodeName+'"><p class="btn btn-warning item-del">删除<p></td>';
        tr+='</tr>';
        adding.append(tr);
    }

    function clearData() {
        node_name.val('');
        description.val('');
        range.val(0);
        is_on.val(1);
    }

    $(document).ready(function () {
        /**
         * 添加所有
         */
        add_all.on('click',function () {
            if(adding_data.length > 0){
                $.comAjax({
                    url:addAllUrl,
                    data:{addArray:adding_data},
                    success:function (ret) {
                        if(ret.status == '200'){
                            adding.empty();
                            adding_data=[];
                            clearData();
                            alert('添加成功');
                            window.location.reload();
                        }
                    }
                });
            }
        });

        /**
         * 删除节点
         */
        adding.delegate('.item-del','click',function () {
            var node={};
            node.level=$(this).parent().attr('level');
            node.nodeName=$(this).parent().attr('nodeName');
            delItem(node);
            $(this).parent().parent().remove();
        });

        /**
         * 修改节点类型
         */
        level.on('change',function () {
            var nodeType=$(this).val();
            fillParents(nodeType);
        });

        /**
         * 添加待添加节点
         */
        adding_node.on('click',function () {
            var node={};
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
                    if(checkExists(node))
                    {
                        is_on.siblings('.war').html('节点已经在待添加列表');
                        return;
                    }
                    clearData();
                    adding_data.push(node);
                    fillAddingData(node);
                }
            }
        });
    });
</script>

