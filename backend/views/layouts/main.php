<?php
    use yii\helpers\Html;
    use common\widgets\Alert;
    use yii\widgets\Breadcrumbs;
    use backend\components\AccessFilter;

    \backend\assets\AppAsset::register($this);

    \backend\assets\CommonAsset::register($this);
    /**
     * 当前模块控制器列表
     */
    $controllerList=[];

    $items=[];

    if(isset($this->params[AccessFilter::MENU]) && is_array($this->params[AccessFilter::MENU]))
    {
        foreach ($this->params[AccessFilter::MENU] as $key=>$module)
        {
            if(!isset($module['isShow']) || $module['isShow'] != '1')
            {
                continue;
            }

            if($module['name'] == $this->params[AccessFilter::MODULE_ID])
            {
                array_push($items,['label'=>Html::encode($module['description']),'url'=>['/'.$module['name']],'options'=>['class'=>'active']]);
                $controllerList=$module['controllerList'];

                continue;
            }
            array_push($items,['label'=>Html::encode($module['description']),'url'=>['/'.$module['name']]]);
        }
    }
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">

        <?= Html::csrfMetaTags() ?>

        <title><?= Html::encode($this->title) ?></title>

        <?php $this->head() ?>

    </head>

    <body>
        <?php $this->beginBody() ?>
            <div class="wrapper">

                <?=$this->renderFile(__DIR__.'/header.php',['items'=>$items])?>

                <div class="content-wrapper">

                    <div class="col-sm-1" style="display: table-cell;">

                        <?=$this->renderFile(__DIR__.'/left.php',['controllerList'=>$controllerList])?>

                    </div>

                    <div class="col-sm-11" style="display: table-cell;background: #ffffff;">

                        <?= Breadcrumbs::widget([
                            'homeLink'  => [
                                'label' => '首页',
                                'url'   => ['/'],
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>

                        <?=Alert::widget()?>

                        <?=$content?>

                    </div>

                </div>
            </div>
             <script type="text/javascript">
                window.staticUrl='<?=FILE_URL?>';
            </script>
        <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>