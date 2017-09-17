<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['name', 'parent_id', 'url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
        ];
    }
    //查询上级分类
    public static function getParents(){
        $parents1=self::find()->where(['=','parent_id',0])->asArray()->all();
        $parents=ArrayHelper::map($parents1,'id','name');
        return ArrayHelper::merge([0=>'顶级分类'],$parents);
    }
    //设置权限循环遍历在视图显示
    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getPermissions();
        //$items=array_keys($permissions);
        $items=ArrayHelper::map($permissions,'name','name');
        return $items;
    }

}
