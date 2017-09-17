<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->textInput(['type'=>'password']);
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'禁用',1=>'启用']);
echo $form->field($model,'roles',['inline'=>true])->checkboxList(\backend\models\Admin::getRole());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
