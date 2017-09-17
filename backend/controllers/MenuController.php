<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Menu::find();
        //实例化分页工具条
        $pager=new Pagination([
            //总页数
            'totalCount'=>$query->count(),
            //每页多少条
            'defaultPageSize'=>15,
        ]);
        //查询计算页面后的数据
        $model=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$model,'pager'=>$pager]);
    }
    //添加菜单
    public function actionAdd(){
        $model=new Menu();
        $request=\Yii::$app->request;
        $auth=\Yii::$app->authManager;
        if($request->isPost){
            $model->load($request->post());
//            var_dump($request->post());exit;
            if($model->validate()){
//                var_dump(\Yii::$app->controller->id);exit;
//                var_dump($request->post());exit;
                $model->save();
                \Yii::$app->session->setFlash('success','菜单添加成功');
                return $this->redirect(['menu/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=Menu::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功!');
        return $this->redirect(['menu/index']);
    }

}
