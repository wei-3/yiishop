<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\filters\AccessControl;


class ArticleController extends \yii\web\Controller
{
    //显示页面
    public function actionIndex()
    {
        $query=Article::find();
        //实例化工具条
        $pager=new Pagination([
           'totalCount'=>$query->where(['>','status',-1])->count(),
            //每页多少条
            'defaultPageSize'=>2,
        ]);
        //查询计算页面数据
        $models=$query->where(['>','status',-1])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        //实例化文章模型
        $model_article=new Article();
        //实例化文章详情模型
        $model_detail=new ArticleDetail();
        $request=\Yii::$app->request;

        if($request->isPost){
            $model_article->load($request->post());
            $model_detail->load($request->post());
            if($model_article->validate()&&$model_detail->validate()){
                $model_article->save(false);
                $model_detail->article_id=$model_article->id;
                $model_detail->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article/index']);
            }
        }
        //实例化文章分类模型查询所有数据
        $model_categorys=ArticleCategory::find()->all();
//        var_dump($model_categorys);exit;
        return $this->render('add',['model_article'=>$model_article,'model_detail'=>$model_detail,'model_categorys'=>$model_categorys]);
    }
    public function actionEdit($id){
        $model_article=Article::findOne(['id'=>$id]);
        $model_detail=ArticleDetail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model_article->load($request->post());
            $model_detail->load($request->post());
            if($model_article->validate()&&$model_detail->validate()){
                $model_article->save(false);
                $model_detail->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['article/index']);
            }
        }
        //实例化文章分类模型查询所有数据
        $model_categorys=ArticleCategory::find()->all();
        return $this->render('add',['model_article'=>$model_article,'model_detail'=>$model_detail,'model_categorys'=>$model_categorys]);
    }
    //删除
    public function actionDel(){
        $id=\Yii::$app->request->post();
        $model=Article::findOne(['id'=>$id]);
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
