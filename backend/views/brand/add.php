<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7 0007
 * Time: 下午 3:17
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea(['rows'=>5]);
echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>'70px']);
echo $form->field($model,'file')->fileInput();
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'statu',['inline'=>true])->radioList(['隐藏','正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
