<?php
/* @var $this yii\web\View */
?>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['article/add'])?>"> 添加文章&nbsp;<span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>
<table class="table table-bordered table-responsive active text-info table-hover">
    <tr class="success">
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->categroy->name?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?'正常':'隐藏'?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article-detail/index','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
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
$del_url=\yii\helpers\Url::to(['article-category/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $('.del_btn').click(function() {
      if(confirm('确定要删除吗?')){
          var tr=$(this).closest('tr');
          var id=tr.attr('data-id');
          $.post("{$del_url}",{id:id},function(data) {
            if(data=='success'){
                  tr.fadeToggle();
                  alert('删除成功');
            }else {
                alert('删除失败');
            }
          })
      }
    })
JS

));
?>
