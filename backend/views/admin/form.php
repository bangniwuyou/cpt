<?php
use backend\components\Html;
use \yii\helpers\Url;

$this->title =isset($info) ? '修改管理员' : '添加管理员';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="col-sm-12">
        <h3>管理员属性</h3>
        <div class="form-group">
            <label class="control-label" for="user_name">管理员登录名</label>
            <input type="text" id="user_name" value="<?=isset($info['userName'])?Html::encode($info['userName']):''?>" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <label class="control-label" for="true_name">管理员真实姓名</label>
            <input type="text" id="true_name" autocomplete="false" value="<?=isset($info['trueName'])?Html::encode($info['trueName']):''?>" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>

        <?php if(!isset($info)):?>
            <div class="form-group">
                <label class="control-label" for="true_name">登录密码</label>
                <input type="password" id="password" autocomplete="false" class="form-control" maxlength="64">

                <div class="help-block war"></div>
            </div>
        <?php endif;?>

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
    var isAdd='<?=isset($info) ? '0' : '1'?>';

    $(document).ready(function () {
        $('.select-edit').each(function (index) {
            var value=$(this).attr('attrId');
            $(this).val(value);
        });

        $('.btn-success').on('click',function(){
            var item={
                id:'<?=isset($info['id']) ? $info['id'] : ''?>'
            };
            item.userName=checkRequire($('#user_name'),'管理员登录名称');
            if(item.userName){
                item.trueName=checkRequire($('#true_name'),'真实姓名');
                if(item.trueName){
                    if(isAdd == '1'){
                        item.password=checkRequire($('#password'),'登录密码');
                        if(!item.password){
                            return;
                        }
                    }
                    item.password=$.md5(item.password);
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
            }
        });
    });
</script>