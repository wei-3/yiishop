<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\filters\AccessControl;


class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        //实例化分页工具条
        $pager=new Pagination([
            //总页数
            'totalCount'=>$query->where(['>','status','-1'])->count(),
            //每页多少条
            'defaultPageSize'=>2,
        ]);
        //查询计算页面后的数据
        $model=$query->where(['>','status','-1'])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$model,'pager'=>$pager]);
    }
    //添加功能
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //根据id查询数据
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($model){
            $model->status=-1;
            $model->save(false);
            return 'success';
        }
        return 'fail';
    }
    public function actions()
    {
        return [
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
