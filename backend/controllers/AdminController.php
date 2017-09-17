<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\CheckpwdForm;
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
        $auth=\Yii::$app->authManager;
        $model->scenario=Admin::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            var_dump($request->post());exit;
            //验证数据
            if($model->validate()){
                //在保存之前要在模型中处理逻辑数据
//                var_dump($model);exit;
//                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
//                var_dump($model->roles);exit;
                $model->save(false);
                $id=\Yii::$app->db->getLastInsertID();
//                var_dump($id);exit;
//                var_dump($model->roles);exit;
            if($model->roles){
                foreach ($model->roles as $roleName){
                    $role=$auth->getRole($roleName);
                    $auth->assign($role,$id);
                }
            }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $auth=\Yii::$app->authManager;
        $model=Admin::findOne(['id'=>$id]);
        $roles=$auth->getRolesByUser($id);
         $model->roles=array_keys($roles);
//        var_dump($roles);exit;
//        $model->roles=$role;
        if($model==null){
            throw new NotFoundHttpException('该用户不存在');
        }
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                $auth->revokeAll($id);
                if($model->roles){
                    foreach ($model->roles as $roleName){
                        $role=$auth->getRole($roleName);
                        $auth->assign($role,$id);
                    }
                }
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel(){
        $id=\Yii::$app->request->post('id');
        $model=Admin::findOne($id);
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRolesByUser($id);
        $model->roles=array_keys($roles);
        if($model->roles){
            $auth->revokeAll($id);
        }
        if($model){
            $model->delete();
            return 'success';
        }else{
            return 'fail';
        }
    }
    public function actionTest(){
        var_dump(\Yii::$app->user->can('user/add'));
    }
    //修改自己的密码
    public function actionPwd(){
        //判断是否为游客
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['login']);
        }
        $model=new CheckpwdForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证旧密码 把它自定义为了验证规则
//                \Yii::$app->user->identity =====  $model_admin=Admin::findOne(['id'=>\Yii::$app->user->id])
//                if(\Yii::$app->security->validatePassword($model->old_pwd,\Yii::$app->user->identity->password_hash)){
//                    //执行密码更新
//                }else{
//                    $model->addError('old_pwd','旧密码不正确');
//                }
                $admin=\Yii::$app->user->identity;
//                var_dump($admin);exit;
                $admin->password=$model->new_pwd;
//                var_dump( $admin->password);exit;
                $admin->save();
                \Yii::$app->session->setFlash('success','修改密码成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('pwd',['model'=>$model]);
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
//    public function behaviors()
//    {
//        return [
//            'acf'=>[
//                'class'=>AccessControl::className(),
//                //不对以下操作有效
//               'except'=>['logout','login'],
//                'rules'=>[
//                    [
//                        'allow'=>true,//是否允许
//                        'actions'=>['index','edit','add','del','user','edit2'],//指定的操作
//                        'roles'=>['@']//指定的角色 ？表为登录
//                    ],
//                ],
//            ]
//        ];
//    }
}
