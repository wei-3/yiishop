<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use yii\data\Pagination;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=new GoodsSearchForm();
        $query=Goods::find();
        $model->search($query);
        //实例化工具条
        $pager=new Pagination([
            'totalCount'=>$query->andWhere(['>','status',-1])->count(),
            //每页多少条
            'defaultPageSize'=>4,
        ]);
        //计算后
        $models=$query->andWhere(['>','status',-1])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['goods_model'=>$models,'pager'=>$pager,'model'=>$model]);
    }
    //添加
    public function actionAdd(){
        $model=new Goods();
        $model_intro=new GoodsIntro();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model_intro->load($request->post());
//            var_dump($model);
//            var_dump($model_intro);exit;
            if($model->validate()&&$model_intro->validate()){
                //查出当天是否有商品上架.
                $goods_day_count=GoodsDayCount::findOne(['day'=>date("Y-m-d",time())]);
                if($goods_day_count){
                    //如果有就count+1
                    $goods_day_count->count+=1;//给count+1

                    //或者 str_pad(string,length,pad_string,pad_type)
//                        string      //必需。规定要填充的字符串。
//                        length      //必需。规定新的字符串长度。如果该值小于字符串的原始长度，则不进行任何操作。
//                        pad_string  //可选。规定供填充使用的字符串。默认是空白。
//                        pad_type    //可选。规定填充字符串的哪边。
//                        //可能的值补的类型：
//                        STR_PAD_BOTH - //填充字符串的两侧。如果不是偶数，则右侧获得额外的填充。
//                        STR_PAD_LEFT - //填充字符串的左侧。
//                        STR_PAD_RIGHT - //填充字符串的右侧。默认。
                    //用这函数    0是占位符 占5个长度 d代表十进制数 拼接
                    $model->sn=date("Ymd",time()).sprintf('%05d',$goods_day_count->count);
                }else{
                    //如果没有 就添加新的 默认count为1
                    $goods_day_count = new GoodsDayCount();
                    $goods_day_count->day = date("Ymd",time());
                    $goods_day_count->count = 1;
                    $model->sn = date("Ymd",time()).'00001';
                }
                $goods_day_count->save();
                $model->view_times = 0;
                $model->status = 1;
                $model->save();
                $model_intro->goods_id=$model->id;
                $model_intro->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
                var_dump($model_intro->getErrors());exit;
            }
        }
        $model_brands=Brand::find()->where(['>','statu',-1])->all();
        return $this->render('add',['model'=>$model,'model_brands'=>$model_brands,'model_intro'=>$model_intro]);
    }
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $model_intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model_intro->load($request->post());
            if($model_intro->validate()&&$model->validate()){
                $model->save();
                $model_intro->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
                var_dump($model_intro->getErrors());exit;
            }
        }
        $model_brands=Brand::find()->where(['>','statu',-1])->all();
        return $this->render('add',['model'=>$model,'model_intro'=>$model_intro,'model_brands'=>$model_brands]);
    }
    //删除
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=Goods::findOne(['id'=>$id]);
        if($model){
            $model->status=-1;
            $model->save(false);
            return 'success';
        }
        return 'fail';
    }
    public function actionDetail($id){
//        var_dump($id);
        $model=Goods::findOne(['id'=>$id]);
        $detail=GoodsIntro::findOne(['goods_id'=>$id]);
        $gallerys=GoodsGallery::find()->where(['goods_id'=>$id])->all();
//        var_dump($gallerys);exit;
        return $this->render('detail',['detail'=>$detail,'gallerys'=>$gallerys,'model'=>$model]);
    }
    //选择文件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
//                    $action->output['fileUrl'] = $action->getWebUrl();//获取图片路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"绝对路径
                    //将图片上传到七牛云，并且返回七牛云的图片地址
                    //加载配置
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    //获取图片路径
                    $key = $action->getWebUrl();
                    //上传到七牛云,同时制定一个key（名称，文件名）
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    //获取七牛云上文件的url地址
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] =$url;
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' =>[
                    "imageUrlPrefix"  => "",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ]
            ],
        ];
    }
}
