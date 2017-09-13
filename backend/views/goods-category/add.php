<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
//=========ztree===========
echo "<div><ul id=\"treeDemo\" class=\"ztree\"></ul></div><br>";
//=========ztree===========
echo $form->field($model,'intro')->textarea(['type'=>3]);
/**
 * @var $this \yii\web\View
 */
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
//注册ztree静态资源和js
//注册css文件
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册js文件(需要在jquery后面加载)
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$goodsCategories=json_encode(\backend\models\GoodsCategory::getZNodes());
$this->registerJs(new \yii\web\JsExpression(
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
                  console.log(treeNode);
                  //获取当前点击节点的id，写入parent_id的值
                  $('#goodscategory-parent_id').val(treeNode.id)
                }
            }
        };
        var zNodes ={$goodsCategories};
       
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
              //全部展开所有节点
           zTreeObj.expandAll(true);
            //修改时根据当前分类的parent_id来选中id
            //获取所需要选中的节点
            
            var node=zTreeObj.getNodeByParam('id',"{$model->parent_id}",null);
            // console.log(node);
            zTreeObj.selectNode(node);
JS

));
