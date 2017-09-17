<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=GoodsCategory::find();
        //实例化分页工具条
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            //每页多少条
            'defaultPageSize'=>4,
        ]);
        //计算后
        $models=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断是否是顶级分类
                if($model->parent_id){
                    //子分类
                   $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $parent_id=$model->parent_id;
        $old_lft=$model->lft;
        $old_rgt=$model->rgt;
//        var_dump($parent_id);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            var_dump($request->post());exit;

            if($model->validate()){
                if($parent_id<$model->parent_id){
                    \Yii::$app->session->setFlash('error','不能修改!');
                    return $this->redirect(['goods-category/index']);
                }
                //判断是否是顶级分类
                if($model->parent_id){
                    //子分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    //判断旧的属性parent_id是否为0对象的旧属性
                    //1、查询数据表，获取旧的parent_id
                    //2、直接获取当前
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }

                }
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['goods-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=GoodsCategory::findOne($id);
//        //判断是否有子节点
//        var_dump($model->isLeaf());exit;
        if($model->isLeaf()){//是否是叶子节点
            $model->deleteWithChildren();
            return 'success';
        }else{
            return 'pass';
        }
        return 'fail';
    }
    public function actionZtree(){
        $goodsCategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($goodsCategories);exit;
        return $this->renderPartial('ztree',['goodsCategories'=>$goodsCategories]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

}
