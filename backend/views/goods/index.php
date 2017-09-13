<?php
/* @var $this yii\web\View */
?>
<style>
    .sub{
        margin-bottom: 12px;
        /*margin-left: 20px;*/
    }
</style>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['goods/add'])?>"> 添加商品&nbsp;<span aria-hidden="true">&rarr;</span> </a></li>
    </ul>
</nav>
<!--搜索框-->
<?php
$form=\yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>"btn btn-default glyphicon glyphicon-search sub"]);
\yii\bootstrap\ActiveForm::end();
?>
<!-- -->
<table class="table table-bordered table-responsive active text-info table-hover ">
    <tr class="success">
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods_model as $model): ?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->sn?></td>
            <td><?=$model->name?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><img src="<?=($model->logo)==''?'/upload/2.jpg':$model->logo?>" class="img-circle" width="50px"></td>
            <td class="col-md-4">
                <a href="<?=\yii\helpers\Url::to(['goods-gallery/gallery','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-book">相册</span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil">编辑</span></a>
                <a href="javascript:;" class="btn btn-default del_btn" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash">删除</span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open">预览</span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
//注册js代码
$del_url=\yii\helpers\Url::to(['goods/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        $(".del_btn").click(function() {
          if(confirm('确定要删除吗')){
              var tr=$(this).closest('tr');
              var id=tr.attr('data-id');
              $.post("{$del_url}",{id:id},function(data) {
                 if(data=='success'){
                    tr.fadeToggle();
                     alert('删除成功');
                }else{
                    alert('删除失败');
                }
              })
          }
        });
JS

));
?>
