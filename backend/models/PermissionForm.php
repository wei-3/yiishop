<?php
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $name;//权限名称
    public $description;//权限的描述
    const SCENARIO_ADDPERMISSION='add-permission';
    const SCENARIO_EDITPERMISSION='edit-permission';

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADDPERMISSION],
            ['name','validateEditName','on'=>self::SCENARIO_EDITPERMISSION],
        ];
    }
    //自定义方法 权限名称
    public function validateName(){
        if(\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
    public function validateEditName(){
        if(\Yii::$app->request->get('name')!=$this->name&&\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限描述',
        ];
    }
}
