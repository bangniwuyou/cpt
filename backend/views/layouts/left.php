<?php
    use backend\components\AccessFilter;
    use yii\helpers\Url;
    use yii\helpers\Html;
?>
<style>
    /* Custom Styles */
    ul.nav-tabs{
        width: 140px;
        margin-top: 20px;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
    }
    ul.nav-tabs li{
        margin: 0;
        border-top: 1px solid #ddd;
    }
    ul.nav-tabs li:first-child{
        border-top: none;
    }
    ul.nav-tabs li a{
        margin: 0;
        padding: 8px 16px;
        border-radius: 0;
    }
    ul.nav-tabs li.active a, ul.nav-tabs li.active a:hover{
        color: #fff;
        background: #0088cc;
        border: 1px solid #0088cc;
    }
    ul.nav-tabs li:first-child a{
        border-radius: 4px 4px 0 0;
    }
    ul.nav-tabs li:last-child a{
        border-radius: 0 0 4px 4px;
    }
    ul.nav-tabs.affix{
        top: 30px; /* Set the top position of pinned element */
    }
</style>
<ul id="nav-left-container" class="nav nav-tabs nav-stacked" data-spy="affix" data-offset-top="125">
    <?php if(!empty($controllerList)):?>
        <?php foreach ($controllerList as $controller):?>
            <?php if(isset($controller['isShow']) && $controller['isShow'] ==1):?>
                <li <?php echo ($this->params[AccessFilter::CONTROLLER_ID]==$controller['name']) ? ' class="active" ' : ' ';?> >
                    <a href="<?=Url::toRoute(['/'.$this->params[AccessFilter::MODULE_ID].'/'.$controller['name']])?>">
                        <?=Html::encode($controller['description'])?>
                    </a>
                </li>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
</ul>