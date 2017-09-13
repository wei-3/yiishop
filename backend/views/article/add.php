<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8 0008
 * Time: 上午 11:54   $model_article  $model_detail  $model_categorys
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model_article,'name')->textInput();
echo $form->field($model_article,'intro')->textarea(['rows'=>3]);
echo $form->field($model_article,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($model_categorys,'id','name'));
echo $form->field($model_article,'sort')->textInput(['type'=>'number']);
//echo $form->field($model_detail,'content')->textarea(['rows'=>5]);
echo $form->field($model_detail,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions'=>[
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
    ]
]);
echo $form->field($model_article,'status',['inline'=>true])->radioList(['隐藏','正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
