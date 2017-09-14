<?php
/**
 * @var $this \yii\web\View
 */
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
//echo $form->field($model,'logo')->hiddenInput();
//外部TAG  文件上传
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id' => $goods->id],//发送请求会以post方式发送  下面也会用得到
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
//       $("#goods-logo").val(data.fileUrl);
        var html='<tr data-id="'+data.id+'">'
        html += '<td><img src="'+data.fileUrl+'" /></td>';
        html += '<td><button type="button" class="btn btn-default del_btn">删除</button></td>';
        html += '</tr>';
        $("table").append(html);
    }
}
EOF
        ),
    ]
]);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-striped">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($gallerys  as  $gallery):?>
        <tr data-id="<?=$gallery->id?>">
            <td><?=\yii\bootstrap\Html::img($gallery->path)?></td>
            <td><?=\yii\bootstrap\Html::button('删除',['class'=>'btn btn-default del_btn'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//注册js代码
$del_url=\yii\helpers\Url::to(['goods-gallery/del']);
$this->registerJs(new JsExpression(
    <<<JS
        $("table").on('click',".del_btn",function(){
        if(confirm("确定删除该图片吗?")){
        var tr=$(this).closest('tr');
         var id=tr.attr('data-id');
            $.post("{$del_url}",{id:id},function(data){
                if(data=="success"){
                    //alert("删除成功");
                   tr.remove();
                }
            });
        }
    });
JS

))
?>
