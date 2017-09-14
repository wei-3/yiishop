<?php
$form=\yii\bootstrap\ActiveForm::begin();
//['readonly'=>'true']
echo $form->field($model,'username')->textInput(['readonly'=>'true']);
echo $form->field($model,'old_pwd')->textInput(['type'=>'password']);
echo $form->field($model,'new_pwd')->textInput(['type'=>'password']);
echo $form->field($model,'re_pwd')->textInput(['type'=>'password']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
