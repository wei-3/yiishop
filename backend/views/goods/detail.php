<?php
/**
 * @var $this \yii\web\View
 */
?>
<div class="panel panel-default">
    <div class="panel-heading text-center"><h2><?=$model->name?></h2></div>
    <div class="panel-body">&nbsp;
        <?php
            foreach ($gallerys as $gallery){
                $form=\yii\bootstrap\ActiveForm::begin();
                echo \yii\bootstrap\Html::img($gallery->path);
                \yii\bootstrap\ActiveForm::end();
            }
//        var_dump($gallerys);
        ?>
       &nbsp;&nbsp;<?=$detail->content?>
    </div>
</div>
