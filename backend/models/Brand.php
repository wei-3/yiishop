<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $statu
 */
class Brand extends \yii\db\ActiveRecord
{
    public $file;//保存文件上传的对象
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['sort', 'statu'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            //验证文件上传文件 extensions指的是文件的扩展名
            ['file','file','extensions'=>['jpg','png']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '简介',
            'logo' => 'logo',
            'sort' => '排序',
            'statu' => '状态',
        ];
    }
}
