<?php
$form=\yii\bootstrap\ActiveForm::begin();
//['readonly'=>'true']
echo $form->field($model,'old_pwd')->passwordInput();
echo $form->field($model,'new_pwd')->passwordInput();
echo $form->field($model,'re_pwd')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();