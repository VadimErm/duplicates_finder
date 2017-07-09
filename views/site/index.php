<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

?>

<div class="row">
    <div class="col-lg-5">

        <?php if (Yii::$app->session->hasFlash('dirNotExist')){ ?>
            <div class="alert alert-danger" role="alert">Dir does not exist!</div>
        <?php } elseif (Yii::$app->session->hasFlash('dirIsEmpty')){ ?>
        <div class="alert alert-warning"" role="alert">Dir is empty.</div>
    <?php }elseif(Yii::$app->session->hasFlash('error')){ ?>
        <div class="alert alert-danger" role="alert">Error!</div>
    <?php } elseif (Yii::$app->session->hasFlash('noDuplicates')) { ?>
    <div class="alert alert-warning"" role="alert">Duplicates not found.</div>
<?php } ?>

<?php if(isset($savedFile)):?>
    <a href="<?= Url::to([$savedFile]);?>" >Дубликаты файлов</a>
<?php endif; ?>

<?php $form = ActiveForm::begin(['id' => 'file-input-form']); ?>

<?= $form->field($model, 'path')->textInput(['autofocus' => true]) ?>

<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
</div>