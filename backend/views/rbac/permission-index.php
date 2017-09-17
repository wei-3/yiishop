<?php
?>
<style>
th { text-align: center;}
</style>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>"> 添加权限&nbsp;<span aria-hidden="true">&rarr;</span> </a></li>
    </ul>
</nav>
<table id="table_id_example" class="table table-bordered table-responsive active text-info table-hover display">
    <thead>
    <tr class="success">
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $permission):?>
    <tr data-id="<?=$permission->name?>">
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
<?php endforeach;?>
    </tbody>
</table>
<?php
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
//注册js代码
$del_url=\yii\helpers\Url::to(['rbac/del-permission']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        $(document).ready( function () {
        $('#table_id_example').DataTable();
} );
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