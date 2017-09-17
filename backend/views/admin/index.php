<?php
/* @var $this yii\web\View */
?>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['admin/add'])?>"> 添加用户&nbsp;<span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>
<table class="table table-bordered table-responsive active text-info table-hover">
    <tr class="success">
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=$model->status?'启用':'禁用'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil">修改</span></a>
                <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash"">删除</span></a>
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
$del_url=\yii\helpers\Url::to(['admin/del']);
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
                else{
                     alert('删除失败');
                }
              });
          }
        });
JS

));
?>
