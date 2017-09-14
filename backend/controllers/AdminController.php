<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginFrom;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

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
        $model->scenario=Admin::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //在保存之前要在模型中处理逻辑数据
//                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Admin::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('该用户不存在');
        }
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=Admin::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['admin/index']);
    }
    public function actionEdit2($id){
        //判断是否为登录状态
        if(\Yii::$app->user->identity){//登录成功
            //获取登录后的用户id
            $log_id = \Yii::$app->user->id;
//            var_dump($id);exit;
            //判断登录后的id与修改时传过来的id是否匹配
            if($log_id==$id){//成功
                //根据传过来的id查询数据
                $model=Admin::findOne(['id'=>$id]);
                $model->scenario=Admin::SCENARIO_UEDIT;
                $request=\Yii::$app->request;
                //判断是否为post请求
                if($request->isPost){
                    $model->load($request->post());
                    if($model->validate()){//验证数据
                        //判断旧密码是否相等
                        if(\Yii::$app->security->validatePassword($model->old_pwd,$model->password_hash)){
                            //如果成功就判断新密码和确认密码是否相等
                            if($model->new_pwd==$model->re_pwd){
                                //保存之前会调用模型中的beforesava（）方法
//                                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->new_pwd);
                                $model->save();
                                \Yii::$app->session->setFlash('success','修改成功');
                                return $this->redirect(['admin/index']);
                            }else{
                                throw new NotFoundHttpException('新密码和确认密码不一致');
                            }
                        }else{
                            throw new NotFoundHttpException('旧密码错误');
                        }
                    }
                }
                return $this->render('useredit',['model'=>$model]);
            }
            elseif ($log_id==1){
                $model=Admin::findOne(['id'=>$id]);
                if($model==null){
                    throw new NotFoundHttpException('该用户不存在');
                }
                $request=\Yii::$app->request;
                if($request->isPost){
                    $model->load($request->post());
                    if($model->validate()){
                        $model->save();
                        \Yii::$app->session->setFlash('success','修改成功');
                        return $this->redirect(['admin/index']);
                    }
                }
                return $this->render('add',['model'=>$model]);
            }
            else{
                //不匹配就提示没有此权限
                throw new NotFoundHttpException('你没有此权限');
            }
        }else{
            \Yii::$app->session->setFlash('error','请登录');
            return $this->redirect(['admin/login']);
        }

    }
    //登录
    public function actionLogin(){
        //实例化登录表单模型
        $model=new LoginFrom();
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收表单数据
            $model->load($request->post());
            if($model->validate()){
//                var_dump($model->login());exit();
//                var_dump($model);exit;
                if($model->login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['admin/index']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionUser(){
        //当前用户的身份实例，未认证用户为null
        $identity=\Yii::$app->user->identity;
        var_dump($identity);
    }
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','退出成功');
        return $this->redirect('login');
    }
    public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                //不对以下操作有效
               'except'=>['logout','login'],
                'rules'=>[
                    [
                        'allow'=>true,//是否允许
                        'actions'=>['index','edit','add','del','user','edit2'],//指定的操作
                        'roles'=>['@']//指定的角色 ？表为登录
                    ],
                ],
            ]
        ];
    }
}
