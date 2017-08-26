<?php
use backend\components\Html;
use \yii\helpers\Url;

$this->title ='重置密码';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="col-sm-12">
        <h3>管理员属性</h3>
        <div class="form-group">
            <label class="control-label" for="user_name">管理员登录名</label>
            <input type="text" id="user_name" readonly="readonly" value="<?=$userName?>" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <label class="control-label" for="true_name">新密码</label>
            <input type="password" id="password" autocomplete="false" class="form-control" maxlength="64">

            <div class="help-block war"></div>
        </div>

        <div class="form-group">
            <p class="btn btn-success form-control" >修改</p>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {

        $('.btn-success').on('click',function(){
            var item={
                id:'<?=$id?>'
            };
            item.password=checkRequire($('#password'),'新密码');
            if(item.password){
                item.password=$.md5(item.password);
                $.comAjax({
                    url:'<?=Url::toRoute(['password'])?>',
                    data:item,
                    success:function (ret) {
                        if(ret.status && ret.status == '200'){
                            alert('修改成功');
                            window.location='<?=Url::toRoute(['index'])?>';
                        }
                        else{
                            $('#password').siblings('.war').html(ret.data);
                        }
                    }
                });
            }
        });
    });
</script>