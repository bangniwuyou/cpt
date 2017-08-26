<?php
use backend\components\Html;
use \yii\helpers\Url;

$this->title =isset($info) ? '修改角色' : '添加角色';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="col-sm-12">
        <h3>角色属性</h3>
        <div class="form-group">
            <label class="control-label" for="role_name">角色名称</label>
            <input type="text" id="role_name" value="<?=isset($info['roleName'])?Html::encode($info['roleName']):''?>" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <label class="control-label" for="is_on">是否启用</label>
            <select id="is_on" class="form-control select-edit" attrId="<?=isset($info['isOn']) ? $info['isOn'] :'1'?>">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <p class="btn btn-success form-control" ><?=isset($info) ? '修改' : '添加'?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select-edit').each(function (index) {
            var value=$(this).attr('attrId');
            $(this).val(value);
        });

        $('.btn-success').on('click',function(){
            var item={
                id:'<?=isset($info['id']) ? $info['id'] : ''?>'
            };
            item.roleName=checkRequire($('#role_name'),'角色名称');
            if(item.roleName){
                item.isOn=$('#is_on').val();
                $.comAjax({
                    url:'<?=Url::toRoute([isset($info)?'edit':'add'])?>',
                    data:item,
                    success:function (ret) {
                        if(ret.status && ret.status == '200'){
                            alert('<?=isset($info) ?'修改':'添加'?>成功');
                            window.location='<?=Url::toRoute(['index'])?>';
                        }
                        else{
                            $('#is_on').siblings('.war').html(ret.data);
                        }
                    }
                });
            }
        });
    });
</script>