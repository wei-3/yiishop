<?php
/* @var $this yii\web\View */
?>
<a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-danger">添加品牌</a>

<table class="table table-bordered table-responsive active text-info table-hover">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?'正常':'隐藏'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$model->id])?>" class="btn btn-danger btn-group-sm">修改</a>
                <a href="<?=\yii\helpers\Url::to(['article-category/del','id'=>$model->id])?>" class="btn btn-danger btn-group-sm">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页'
]);
?>
