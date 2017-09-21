<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property string $name
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $tel
 * @property string $address_detail
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cmbProvince', 'cmbCity', 'cmbArea', 'tel', 'address_detail'], 'required'],
            [['name', 'cmbProvince', 'cmbCity', 'cmbArea', 'address_detail'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cmbProvince' => 'Cmb Province',
            'cmbCity' => 'Cmb City',
            'cmbArea' => 'Cmb Area',
            'tel' => 'Tel',
            'address_detail' => 'Address Detail',
        ];
    }
}
