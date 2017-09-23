<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170922_102614_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->comment('订单id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'goods_name'=>$this->string()->comment('商品名称'),
            'logo'=>$this->string()->comment('图片'),
            'price'=>$this->decimal()->comment('价格'),
            'amount'=>$this->integer()->comment('数量'),
            'total'=>$this->decimal()->comment('小计'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
