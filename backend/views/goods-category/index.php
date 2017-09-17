<?php
/* @var $this yii\web\View */
?>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>"> 添加商品分类&nbsp;<span aria-hidden="true">&rarr;</span> </a></li>
    </ul>
</nav>
<table class="table table-bordered table-responsive active text-info table-hover">
    <tr class="success">
        <th>ID</th>
        <th>商品分类名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?php
                echo str_repeat('==',$model->depth).$model->name;
                ?></td>
            <td><?=$model->intro?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
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
//注册js代码
$del_url=\yii\helpers\Url::to(['goods-category/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        $(".del_btn").click(function() {
          if(confirm('确定要删除吗')){
              var tr=$(this).closest('tr');
              var id=tr.attr('data-id');
              // console.log(id);
              $.post("{$del_url}",{id:id},function(data) {
                if(data=='success'){
                     tr.fadeToggle();
                     alert('删除成功');
                }
                else if(data='pass'){
                    alert('只能删除叶子节点')
                }
                else{
                     alert('删除失败');
                }
              });
          }
        });
JS

));
?>
