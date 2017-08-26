<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = '登陆';
?>

<div class="site-login">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <h1 style="text-align: center;"><?=\Yii::$app->name?></h1>
            <p></p>
            <div class="form-group required" style="margin-top:20px; ">

                <input type="text" id="userName" class="form-control" name="userName" autocomplete="off" placeholder="登录账号">

                <p style="color: red;" class="help-block help-block-error"></p>
            </div>
            <div class="form-group required" style="margin-top:20px; ">
                <input type="password" id="password" class="form-control" name="password" autocomplete="off" placeholder="登录密码">

                <p style="color: red;" class="help-block help-block-error"></p>
            </div>
            <div class="form-group required">
                <?=Captcha::widget([
                    'name' => 'verify',
                    'captchaAction'=>'/login/verify',
                    'imageOptions'=>[
                            'style'=>'cursor: pointer;'
                    ]
                ]);?>

                <p style="color: red;" id="summary" class="help-block help-block-error"></p>
            </div>

            <div class="form-group" style="text-align: center;margin-top: 20px">
                <?= Html::button('登陆', ['class' => 'btn btn-success form-control', 'id' => 'login-button']) ?>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
<script type="text/javascript">
    (function(obj){
        obj.userName=$('#userName');
        obj.passWord=$('#password');
        obj.login=$('#login-button');
        obj.verify=$('#w0');

        obj.init=function(){
            obj.initEvent();
        };

        obj.initEvent=function(){
            obj.verify.on('keyup',function (e) {
                if(e.keyCode == 13){
                    obj.login.click();
                }
            });

            obj.login.on('click',function()
            {
                var userName=obj.checkRequire(obj.userName,'登陆账号');
                if(userName)
                {
                    var password=obj.checkRequire(obj.passWord,'登陆密码');
                    if(password)
                    {
                        var verify=obj.checkRequire(obj.verify,'验证码');
                        if(verify)
                        {
                            $.comAjax({
                                url:'<?=Url::toRoute(['/login/login'])?>',
                                data:{userName:userName,password:$.md5(password),code:verify},
                                success:function (ret)
                                {
                                    if(ret.status=='200')
                                    {
                                        window.location='<?=Url::toRoute([\Yii::$app->homeUrl])?>';
                                    }
                                    else
                                    {
                                        $('#summary').html(ret.data);
                                    }
                                }
                            });
                        }
                    }
                }
                return;
            });

            obj.checkRequire=function (objJquery,msg)
            {
                var value=objJquery.val();
                if(value.length<1)
                {
                    $('.help-block').empty();
                    objJquery.siblings('.help-block').html(msg+'必填');
                    objJquery.focus();
                    value=false;
                }
                return value;
            };
        };
    }(Cell));
</script>