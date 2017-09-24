<?php

namespace frontend\models;

use backend\models\Goods;
use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $amount
 * @property integer $member_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'amount', 'member_id'], 'integer'],
            [['goods_id', 'amount'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'amount' => '商品数量',
            'member_id' => '用户id',
        ];
    }
    //查询goods表中数据
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
    public function getOrderid(){
        return $this->hasMany(Order::className(),['member_id'=>'member_id']);
    }
}
