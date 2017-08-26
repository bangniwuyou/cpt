<?php
    use yii\helpers\Html;
    use www\assets\AppAsset;

    AppAsset::register($this);
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

        <?=$content?>

    <script type="text/javascript">
        window.staticUrl='<?=FILE_URL?>';
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>