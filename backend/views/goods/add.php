<?php
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
//echo $form->field($model,'logo')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//外部TAG  文件上传
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);//返回打印路径
        //将文件上传路径写入logo字段的隐藏域
        $("#goods-logo").val(data.fileUrl);
        //添加时图片的回显
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img']);
//echo $form->field($model,'goods_category_id')->textInput();
echo $form->field($model,'goods_category_id')->hiddenInput();
echo $form->field($model,'cj')->hiddenInput()->label(false);
//=========ztree===========
echo "<div><ul id=\"treeDemo\" class=\"ztree\"></ul></div><br>";
//=========ztree===========
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($model_brands,'id','name'));
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale')->radioList([0=>'下架',1=>'上架']);
echo $form->field($model,'sort')->textInput(['type'=>'number']);
//echo $form->field($model_intro,'content')->textInput();
echo $form->field($model_intro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions'=>[
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
/**
 * @var $this \yii\web\View
 */
//注册ztree静态资源和js
//注册css文件
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册js文件(需要在jquery后面加载)
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//把数据转换为json对象
$goodsCategories=json_encode(\backend\models\GoodsCategory::getZNodes());
$this->registerJs(new JsExpression(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{//事件回调函数，点击节点会弹出该节点的parent_id,name的信息
                onClick:function(event, treeId, treeNode) {
                  //获取当前点击节点的id，写入parent_id的值
                  $('#goods-goods_category_id').val(treeNode.id);
                  $('#goods-cj').val(treeNode.level);
                  console.log(treeNode.level);
                  
                }
            }
        };
        var zNodes ={$goodsCategories};

            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
              //全部展开所有节点
           zTreeObj.expandAll(true);
            //修改时根据当前分类的goods_category_id来选中id
            //获取所需要选中的节点
            var node=zTreeObj.getNodeByParam('id',"{$model->goods_category_id}",null);
            // console.log(node);
            zTreeObj.selectNode(node);
JS

));
