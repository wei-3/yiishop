<?php

namespace backend\controllers;

use backend\models\Admin;
use yii\data\Pagination;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Admin::find();
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>3,
        ]);
        //查询计算页面后的数据
        $models=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Admin();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            var_dump($request->post());exit;
            if($model->validate()){
//                var_dump($model->getErrors());exit;
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $model->save(false);
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){

    }

}
