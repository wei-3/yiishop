<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{

    public function actionIndex()
    {
        //查询数据
        $query=Brand::find();
        //实例化工具条
        $pager=new Pagination([
            'totalCount'=>$query->where(['>','statu','-1'])->count(),
            'defaultPageSize'=>2,
        ]);
        //查询计算页面后的数据
        $model=$query->where(['>','statu','-1'])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$model,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //处理上传文件 实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()){
                if ($model->file){
                    //移动文件 move_uploaded_file()
                    $file='/upload/'.uniqid().'.'.$model->file->getExtension();//文件名（含路径）
                    //保存文件 指定路径
                    $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                    $model->logo=$file;//上传文件的地址
                }
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        //根据id查询数据
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //处理上传文件 实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()){
                if ($model->file){
                    //移动文件 move_uploaded_file()
                    $file='/upload/'.uniqid().'.'.$model->file->getExtension();//文件名（含路径）
                    //保存文件 指定路径
                    $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                    $model->logo=$file;//上传文件的地址
                }
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->statu=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功!');
        return $this->redirect(['brand/index']);
    }
}
