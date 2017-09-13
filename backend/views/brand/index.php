<?php
/* @var $this yii\web\View */
?>
<style>
  th { text-align: center;}
</style>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['brand/add'])?>"> 添加品牌&nbsp;<span aria-hidden="true">&rarr;</span> </a></li>
    </ul>
</nav>
<table class="table table-bordered table-responsive active text-info table-hover">
    <tr class="success">
        <th>ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>品牌图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><img src="<?=($model->logo)==''?'/upload/2.jpg':$model->logo?>" class="img-circle" width="70px"></td>
            <td><?=$model->sort?></td>
            <td><?=$model->statu?'正常':'隐藏'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash"></span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
$del_url=\yii\helpers\Url::to(['brand/del']);
//注册js代码
    $this->registerJs(new \yii\web\JsExpression(
            <<<JS
        $(".del_btn").click(function() {
            // alert(111);
          if(confirm('确定要删除吗')){
              var tr=$(this).closest('tr');
              var id=tr.attr("data-id");
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
