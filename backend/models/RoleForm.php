<?php
namespace backend\models;
use function PHPSTORM_META\map;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model{
    public $name;//角色名称
    public $description;//角色的描述
    public $permissions;//权限选择
    public $old_name;
    const SCENARIO_ADDROLE ='add-role';
    const SCENARIO_EDITROLE ='edit-role';
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],//safe是指可写可不写的规则
            ['name','validateName','on'=>self::SCENARIO_ADDROLE],
            ['name','validateEditName','on'=>self::SCENARIO_EDITROLE],
        ];
    }
    //设置权限循环遍历在视图显示
    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getPermissions();
//        foreach ($permissions as $permission)
        $items=ArrayHelper::map($permissions,'name','description');
//        var_dump($items);
        return $items;
    }
    public function validateName(){
        if(\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }
    public function validateEditName(){
        if(\Yii::$app->request->get('name')!=$this->name&&\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'选择权限'
        ];
    }
}
