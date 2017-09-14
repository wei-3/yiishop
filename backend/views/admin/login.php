<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput()->label('用户名');
echo $form->field($model,'password')->textInput(['type'=>'password'])->label('密码');
echo $form->field($model,'remember')->checkbox(['记住我']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
