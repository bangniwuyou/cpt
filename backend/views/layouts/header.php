<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\Nav;
?>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=Url::toRoute([\Yii::$app->homeUrl])?>"><?=Html::encode(\Yii::$app->name)?></a>
        </div>
        <div class="collapse navbar-collapse" id="example-navbar-collapse">
            <ul class="nav navbar-nav">
                <?php foreach ($items as $item):?>
                    <li class="<?=(isset($item['options']['class'])?$item['options']['class']:'')?>"><a href="<?=Url::toRoute($item['url'])?>"><?=Html::encode($item['label'])?></a></li>
                <?php endforeach;?>
            </ul>
            <?php
            echo Nav::widget([
                'items' => [
                    ['label'=>Html::encode($this->params['user']['trueName'])],
                    ['label'=>'注销','url'=>['/login/login-out'],'options'=>['class'=>'nav navbar-nav navbar-right']]
                ],
                'options' => ['class' => 'nav navbar-nav navbar-right'],
            ]);
            ?>
        </div>
    </div>
</nav>