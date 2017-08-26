<?php
    use \yii\helpers\Url;
    use yii\helpers\Html;

    $this->title = '角色分配';
    $this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

?>
<style type="text/css">
    .tr{
        cursor: pointer;
    }

    .select{
        background-color:#5cb85c ;
    }
</style>
<div>
    <h3>
        <?=Html::encode($userName)?>角色分配
    </h3>
    <div class="col-sm-6">
        <h3>角色列表</h3>
        <table class="table" id="not-role">
            <?php foreach ($list as $role):?>
                <?php if(!in_array($role['id'],$ownList)):?>
                    <tr class="tr" roleId="<?=$role['id']?>" roleName="<?=Html::encode($role['roleName'])?>">
                        <td><?=Html::encode($role['roleName'])?></td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
        </table>

        <div class="form-group">
            <p class="btn btn-success form-control" id="adding_role"> &gt;&gt;</p>
        </div>
    </div>
    <div class="col-sm-6">
        <h3>已有角色</h3>
        <table class="table">
            <tr>
                <th>角色名称</th>
                <th>操作</th>
            </tr>
            <tbody id="adding">
                <?php foreach ($ownList as $roleId):?>
                    <?php if(isset($list[$roleId])):?>
                        <tr>
                            <td>
                                <?=Html::encode($list[$roleId]['roleName'])?>
                            </td>
                            <td>
                                <p class="btn btn-danger" roleId="<?=$roleId?>" roleName="<?=Html::encode($list[$roleId]['roleName'])?>">删除</p>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach;?>
            </tbody>
            <tr>
                <td colspan="7">
                    <p class="btn btn-success form-control" id="btn_add_all">更新</p>
                </td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">
    var ownList=<?=json_encode($ownList)?>;

    function addOwnList(id) {
        if(ownList.indexOf(id) > -1){
            return;
        }
        ownList.push(id);
    }

    function removeOwnList(id) {
        var index=ownList.indexOf(id);
        if(index >-1){
            ownList.splice(index,1);
        }
    }

    function addToAdding(item) {
        $('#adding').append('<tr><td>'+item.roleName+'</td><td><p roleId="'+item.roleId+'" class="btn btn-danger" roleName="'+item.roleName+'">删除</p></td></tr>');
    }

    function addToNot(item) {
        $('#not-role').append('<tr class="tr" roleId="'+item.roleId+'" roleName="'+item.roleName+'"><td>'+item.roleName+'</td></tr>');
    }

    $(document).ready(function () {
        $('#not-role').delegate('.tr','click',function () {
            if($(this).hasClass('select'))
            {
                $(this).removeClass('select');
            }
            else{
                $(this).addClass('select');
            }
        });

        $('#adding_role').on('click',function () {
            if($('.tr.select').length>0)
            {
                $('.tr.select').each(function (index) {
                    var roleId=$(this).attr('roleId');
                    var roleName=$(this).attr('roleName');
                    addOwnList(roleId);
                    addToAdding({roleId:roleId,roleName:roleName});
                    $(this).remove();
                });
            }
            else{
                alert('请选择要添加的角色');
            }
        });

        $('#adding').delegate('.btn-danger','click',function () {
            var roleId=$(this).attr('roleId');
            var roleName=$(this).attr('roleName');
            removeOwnList(roleId);
            $(this).parent().parent().remove();
            addToNot({roleId:roleId,roleName:roleName});
        });

        $('#btn_add_all').on('click',function () {
            if(ownList.length<1){
                if(!confirm('您确定要移除该用户的所有角色吗？')){
                    return;
                }
            }
            $.comAjax({
                url:'<?=Url::toRoute(['user-role'])?>',
                data:{list:ownList,id:'<?=$id?>'},
                success:function (ret) {
                    if(ret.status && ret.status == '200'){
                        alert('更新成功');
                    }
                }
            });
        });
    });

</script>

