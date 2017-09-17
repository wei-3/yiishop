<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    //添加权限功能
    public function actionAddPermission(){
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_ADDPERMISSION;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth=\Yii::$app->authManager;
                //添加权限
                //1、创建权限
                $permission=$auth->createPermission($model->name);
                $permission->description=$model->description;
                //2、保存权限
                $auth->add($permission);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['index-permission']);
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    //显示权限
    public function actionIndexPermission()
    {
        $auth=\Yii::$app->authManager;
        //获取所有的权限
        $permissions=$auth->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }
    //修改权限
    public function actionEditPermission($name){
//        var_dump($name);
        $auth=\Yii::$app->authManager;
        $name_permission=$auth->getPermission($name);
        if($name_permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_EDITPERMISSION;
        //给表单赋值并回显
        $model->name=$name_permission->name;
        $model->description=$name_permission->description;
//        var_dump($model);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth=\Yii::$app->authManager;
                $name_permission->name=$model->name;
                $name_permission->description=$model->description;
                 //根据穿过来的$name保存修改
                $auth->update($name,$name_permission);
                \Yii::$app->session->setFlash('success','修改权限成功!');
                return $this->redirect(['rbac/index-permission']);
            }
        }

        return $this->render('permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission(){
        $name=\Yii::$app->request->post('id');
        //根据$name找到权限
        $name_permission=\Yii::$app->authManager->getPermission($name);
        if($name_permission){
            //移除权限
            \Yii::$app->authManager->remove($name_permission);
            return 'success';
        }else{
            return 'fail';
        }
    }
    //添加角色
    public function actionAddRole(){
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_ADDROLE;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth=\Yii::$app->authManager;
                //保存角色
                //1创建新角色
                $role=$auth->createRole($model->name);
                $role->description=$model->description;
                //保存到数据表
                $auth->add($role);
                //给角色分配权限
                if($model->permissions){
//                    var_dump($model->permissions);exit;
                    foreach ($model->permissions as $permissionName){
                        //根据遍历$permissionName查找权限
                        $permission=$auth->getPermission($permissionName);
                        //保存权限与角色的关系
                        $auth->addChild($role,$permission);
                    }
                }
                \Yii::$app->session->setFlash('success','添加角色成功!');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role',['model'=>$model]);
    }
    //显示角色列表
    public function actionRoleIndex(){
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }
    //修改角色
    public function actionEditRole($name){
        $auth=\Yii::$app->authManager;
        $role=$auth->getRole($name);
        //获取该角色的权限
        $permissions=$auth->getPermissionsByRole($name);
//        var_dump($permissions);exit;
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_EDITROLE;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $role->name=$model->name;
                $role->description = $model->description;
                $auth->update($name,$role);
                //更新权限与角色是不同的表，先清除角色与权限的原有的关联
                $auth->removeChildren($role);
                //在重新赋权限
                if($model->permissions) {
                    foreach ($model->permissions as $permissionName) {
                        //根据遍历$permissionName查找权限
                        $permission = $auth->getPermission($permissionName);
                        //保存权限与角色的关系
                        $auth->addChild($role, $permission);
                    }
                }
                \Yii::$app->session->setFlash('success',$role->name.' 角色修改成功');
                return $this->redirect(['rbac/role-index']);
            }
        }
        //表单回显
        $model->name=$role->name;
        $model->description=$role->description;
//        var_dump(is_array($permissions));exit;
        if($permissions){
            $model->permissions=array_keys($permissions);
        }

        return $this->render('role',['model'=>$model]);
    }
    //删除角色
    public function actionDelRole(){
        $auth=\Yii::$app->authManager;
        $name=\Yii::$app->request->post('id');
        //获取当前角色
        $role=$auth->getRole($name);
        //获取该角色的权限
        $permissions=$auth->getPermissionsByRole($name);
        //先判断当前角色是否有权限
        if($permissions){
            $auth->removeChildren($role);
        }
        //删除当前角色
        if($role){
            $auth->remove($role);
            return 'success';
        }else{
            return 'fail';
        }
    }
}
