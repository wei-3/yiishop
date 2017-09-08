<?php
/* @var $this yii\web\View */
?>
<a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-danger">添加品牌</a>

<table class="table table-bordered table-responsive active text-info table-hover">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>品牌图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><img src="<?=($model->logo)==''?'/upload/2.jpg':$model->logo?>" class="img-circle" width="70px"></td>
            <td><?=$model->sort?></td>
            <td><?=$model->statu?'正常':'隐藏'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$model->id])?>" class="btn btn-danger btn-group-sm">修改</a>
                <a href="<?=\yii\helpers\Url::to(['brand/del','id'=>$model->id])?>" class="btn btn-danger btn-group-sm">删除</a>
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
